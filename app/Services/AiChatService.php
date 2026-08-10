<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    /**
     * Predefined intents mapped to safe, parameterized queries.
     */
    private const INTENTS = [
        'stock_check',
        'aging_check',
        'serial_number_lookup',
        'location_status',
        'inbound_status',
        'outbound_summary',
        'inventory_summary',
        'product_history',
        'general_chat',
    ];

    public function __construct()
    {
        $this->apiKey  = config('services.deepseek.api_key');
        $this->baseUrl = config('services.deepseek.base_url', 'https://api.deepseek.com');
        $this->model   = config('services.deepseek.model', 'deepseek-v4-flash');
    }

    /**
     * Process a user message and return the assistant's reply.
     */
    public function chat(string $userMessage, ChatConversation $conversation): string
    {
        // Step 1: Save user message
        ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'role'                 => 'user',
            'content'              => $userMessage,
            'created_at'           => now(),
        ]);

        // Auto-title the conversation from the first user message
        if (empty($conversation->title)) {
            $conversation->title = mb_strlen($userMessage) > 50
                ? mb_substr($userMessage, 0, 47) . '...'
                : $userMessage;
            $conversation->save();
        }

        // Step 2: Try local intent detection first (fast & reliable)
        $localIntent = $this->detectLocalIntent($userMessage);
        if ($localIntent) {
            $intent = $localIntent['intent'];
            $params = $localIntent['params'];
            Log::info('AI Chat intent detected locally', ['intent' => $intent, 'params' => $params]);
        } else {
            // Fall back to AI classification
            $classification = $this->classifyIntent($userMessage, $conversation);
            $intent         = $classification['intent'] ?? 'general_chat';
            $params         = $classification['params'] ?? [];
            Log::info('AI Chat intent classified via AI', ['intent' => $intent, 'params' => $params]);
        }

        // Step 3: Execute query if it's a data intent
        $queryResult = null;
        if ($intent !== 'general_chat') {
            $queryResult = $this->executeIntentQuery($intent, $params);
        }

        // Step 4: Generate natural language reply
        $reply = $this->generateReply($userMessage, $intent, $params, $queryResult, $conversation);

        // Step 5: Save assistant message
        ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'role'                 => 'assistant',
            'content'              => $reply,
            'metadata'             => [
                'intent' => $intent,
                'params' => $params,
            ],
            'created_at'           => now(),
        ]);

        return $reply;
    }

    /**
     * Call DeepSeek to classify the user's intent and extract parameters.
     */
    private function classifyIntent(string $userMessage, ChatConversation $conversation): array
    {
        // If no API key, skip the AI classification call entirely
        if (empty($this->apiKey)) {
            $fallback = $this->detectFollowUpIntent($userMessage, $conversation);
            return $fallback ?: ['intent' => 'general_chat', 'params' => []];
        }

        $systemPrompt = $this->getIntentClassificationPrompt();

        // Get recent conversation for context
        $recentMessages = $this->getRecentContext($conversation);

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $recentMessages,
            [['role' => 'user', 'content' => $userMessage]]
        );

        $result = null;

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl . '/v1/chat/completions', [
                    'model'       => $this->model,
                    'messages'    => $messages,
                    'temperature' => 0.1,
                    'max_tokens'  => 300,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $result = $this->parseJsonResponse($content);
            } else {
                Log::error('DeepSeek intent classification failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('DeepSeek API error: ' . $e->getMessage());
        }

        // Fallback: if classification returned general_chat, check if this is a follow-up
        // to a previous data query by reusing the last known intent & params
        if (!$result || $result['intent'] === 'general_chat') {
            $fallback = $this->detectFollowUpIntent($userMessage, $conversation);
            if ($fallback) {
                Log::info('AI Chat: using fallback intent from conversation history', $fallback);
                return $fallback;
            }
        }

        return $result ?: ['intent' => 'general_chat', 'params' => []];
    }

    /**
     * Detect if the current message is a follow-up to a previous data query.
     * If so, reuse the intent and params from the most recent assistant message.
     */
    private function detectFollowUpIntent(string $userMessage, ChatConversation $conversation): ?array
    {
        // Patterns that indicate a follow-up / continuation request
        $followUpPatterns = [
            'outbound',
            'inbound',
            'history',
            'riwayat',
            'lengkap',
            'detail',
            'data',
            'report',
            'laporan',
            'lagi',
            'cek',
            'coba',
            'tolong',
            'tampilkan',
            'mana',
            'kok',
            'ini',
            'itu',
            'nya',
            'dong',
            'dulu',
            'semua',
            'full',
        ];

        $msgLower = strtolower($userMessage);

        // Check if message is short (likely follow-up) and contains follow-up patterns
        $wordCount = str_word_count($msgLower, 0);
        $hasFollowUpWord = false;
        foreach ($followUpPatterns as $word) {
            if (str_contains($msgLower, $word)) {
                $hasFollowUpWord = true;
                break;
            }
        }

        // Only apply fallback for shorter messages (likely follow-ups, not new topics)
        if (!$hasFollowUpWord || $wordCount > 25) {
            return null;
        }

        // Look for the most recent assistant message that had intent metadata
        $lastAssistantMsg = $conversation->messages()
            ->where('role', 'assistant')
            ->whereNotNull('metadata')
            ->latest('created_at')
            ->first();

        if (!$lastAssistantMsg || empty($lastAssistantMsg->metadata)) {
            return null;
        }

        $metadata = $lastAssistantMsg->metadata;
        $lastIntent = $metadata['intent'] ?? null;
        $lastParams = $metadata['params'] ?? [];

        if (!$lastIntent || $lastIntent === 'general_chat') {
            return null;
        }

        // Try to extract new params from current message, merge with previous params
        $newParams = $this->extractParamsLocally($userMessage, $lastIntent);
        $mergedParams = array_merge($lastParams, $newParams);

        return [
            'intent' => $lastIntent,
            'params' => $mergedParams,
        ];
    }

    /**
     * Detect intent locally from clear patterns in the user message.
     * This bypasses AI classification for fast, reliable data queries.
     * Returns null if no clear pattern is detected (falls back to AI).
     */
    private function detectLocalIntent(string $userMessage): ?array
    {
        $msg = strtolower($userMessage);

        // --- Serial Number Lookup ---
        // Match common SN patterns: FOC2427N5MD, ABC12345678, etc.
        $serialNumber = null;

        // Pattern 1: Explicit "serial number" mention followed by an identifier
        if (preg_match('/serial\s*number\s*(?:ini|itu|nya|adalah|:|#)?\s*([A-Z0-9][\w\-]{4,30}[A-Z0-9])/iu', $userMessage, $m)) {
            $serialNumber = strtoupper(trim($m[1]));
        }
        // Pattern 2: "SN:" or "SN-" prefix
        elseif (preg_match('/\bSN[:\-\s#]+([A-Z0-9][\w\-]{3,30})\b/iu', $userMessage, $m)) {
            $serialNumber = strtoupper(trim($m[1]));
        }
        // Pattern 3: Standalone alphanumeric identifier (mixed letters+digits, 6-30 chars)
        elseif (preg_match('/\b(?=[A-Z]*\d)(?=\d*[A-Z])[A-Z0-9][A-Z0-9\-_]{5,30}\b/iu', $userMessage, $m)) {
            $candidate = strtoupper($m[0]);
            // Filter out common words that happen to be alphanumeric
            $nonSerials = ['WMS', 'TKS', 'API', 'JSON', 'PDF', 'EXCEL', 'CSV', 'HTML', 'CSS', 'PO', 'DN'];
            if (!in_array($candidate, $nonSerials) && strlen($candidate) >= 6) {
                $serialNumber = $candidate;
            }
        }
        // Pattern 4: The original extractParamsLocally regex (flexible)
        if (!$serialNumber) {
            if (preg_match('/\b([A-Z]{2,5}\d{4,12}[A-Z]?\d*[A-Z]*)\b/iu', $userMessage, $m)) {
                $serialNumber = strtoupper($m[1]);
            }
        }

        // If we found a serial number and user is asking about data, force serial_number_lookup
        if ($serialNumber && strlen($serialNumber) >= 5) {
            $dataKeywords = [
                'data', 'info', 'informasi', 'cek', 'cari', 'lihat', 'tolong',
                'berikan', 'tampilkan', 'detail', 'lacak', 'track', 'history',
                'riwayat', 'inbound', 'outbound', 'return', 'status', 'lokasi',
                'ada', 'dimana', 'mana', 'bagaimana', 'apa', 'inventory',
                'semua', 'lengkap', 'full', 'bagi', 'kasih', 'bantu',
            ];

            $hasDataKeyword = false;
            foreach ($dataKeywords as $word) {
                if (str_contains($msg, $word)) {
                    $hasDataKeyword = true;
                    break;
                }
            }

            if ($hasDataKeyword) {
                return [
                    'intent' => 'serial_number_lookup',
                    'params' => ['serial_number' => $serialNumber],
                ];
            }
        }

        // --- Stock Check ---
        if (preg_match('/\b(stok|stock|stoknya|tersedia)\b/i', $msg) && !$serialNumber) {
            $params = $this->extractParamsLocally($userMessage, 'stock_check');
            // Only return if there's at least a part name or part number
            if (!empty($params['part_name']) || !empty($params['part_number'])) {
                return ['intent' => 'stock_check', 'params' => $params];
            }
            // If user says "cek stok" without specifics, still return stock_check with empty params
            if (preg_match('/\b(cek\s*stok|stok\s*apa\s*aja|list\s*stok|semua\s*stok)\b/i', $msg)) {
                return ['intent' => 'inventory_summary', 'params' => []];
            }
        }

        // --- Aging Check ---
        if (preg_match('/\b(aging|lama|tua|90\s*hari|6\s*bulan|setahun|tidak\s*bergerak|stuck|ngendap)\b/i', $msg)) {
            $params = $this->extractParamsLocally($userMessage, 'aging_check');
            if (empty($params['days'])) {
                $params['days'] = 90;
            }
            return ['intent' => 'aging_check', 'params' => $params];
        }

        // --- Inbound Status ---
        if (preg_match('/\b(?:inbound|po|purchase\s*order|barang\s*masuk|receiving)\b.*\b(?:status|nomor|number|no)\b/i', $msg)) {
            $params = $this->extractParamsLocally($userMessage, 'inbound_status');
            if (!empty($params['inbound_number'])) {
                return ['intent' => 'inbound_status', 'params' => $params];
            }
        }

        // --- Outbound Summary ---
        if (preg_match('/\b(outbound|pengiriman|pengeluaran)\b.*\b(?:ringkasan|summary|rekap|bulan|month|semua)\b/i', $msg)) {
            $params = $this->extractParamsLocally($userMessage, 'outbound_summary');
            return ['intent' => 'outbound_summary', 'params' => $params];
        }

        // --- Location Status ---
        if (preg_match('/\b(?:lokasi|area|rak|bin|lantai|simpan|tempat)\b.*\b(?:status|isi|barang|item|apa|ada)\b/i', $msg)) {
            $params = $this->extractParamsLocally($userMessage, 'location_status');
            return ['intent' => 'location_status', 'params' => $params];
        }

        // --- Inventory Summary ---
        if (preg_match('/\b(ringkasan|summary|rekap|total|keseluruhan)\s*(inventori|inventory|stok|stock)\b/i', $msg)) {
            return ['intent' => 'inventory_summary', 'params' => []];
        }

        return null; // No local pattern detected, fall back to AI
    }

    /**
     * Simple local param extraction for follow-up messages.
     * Extracts serial numbers, part numbers, etc. from user text.
     */
    private function extractParamsLocally(string $userMessage, string $intent): array
    {
        $params = [];

        // Try to extract serial number (alphanumeric, typically 8-15 chars)
        if (preg_match('/\b([a-zA-Z]{2,4}\d{5,12}[a-zA-Z]?)\b/', $userMessage, $m)) {
            $params['serial_number'] = $m[1];
        }

        // Try to extract part number
        if (preg_match('/\b([A-Z0-9]{4,}[\-\.\/]?[A-Z0-9]{2,})\b/', $userMessage, $m)) {
            $params['part_number'] = $m[1];
        }

        // Try to extract inbound/PO number
        if (preg_match('/\b(\d{4,}\/[A-Z]{1,4}\/[A-Z]{1,3}\/[A-Z]{1,3}\/\d{4})\b/i', $userMessage, $m)) {
            $params['inbound_number'] = $m[1];
        }

        // Try to extract month/year for outbound summary
        if (preg_match('/\b(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\b/i', $userMessage, $m)) {
            $months = ['januari'=>1,'februari'=>2,'maret'=>3,'april'=>4,'mei'=>5,'juni'=>6,'juli'=>7,'agustus'=>8,'september'=>9,'oktober'=>10,'november'=>11,'desember'=>12];
            $params['month'] = $months[strtolower($m[1])] ?? null;
        }

        return $params;
    }

    /**
     * Generate the final natural language reply.
     */
    private function generateReply(
        string $userMessage,
        string $intent,
        array $params,
        ?array $queryResult,
        ChatConversation $conversation
    ): string {
        if ($intent === 'general_chat') {
            return $this->generateGeneralChatReply($userMessage, $conversation);
        }

        if (empty($queryResult) || (is_array($queryResult) && count($queryResult) === 0)) {
            return $this->formatEmptyResult($intent, $params);
        }

        return $this->formatDataReply($userMessage, $intent, $params, $queryResult, $conversation);
    }

    /**
     * Execute a predefined, safe query based on intent and params.
     */
    private function executeIntentQuery(string $intent, array $params): ?array
    {
        return match ($intent) {
            'stock_check'          => $this->queryStockCheck($params),
            'aging_check'          => $this->queryAgingCheck($params),
            'serial_number_lookup' => $this->querySerialNumber($params),
            'location_status'      => $this->queryLocationStatus($params),
            'inbound_status'       => $this->queryInboundStatus($params),
            'outbound_summary'     => $this->queryOutboundSummary($params),
            'inventory_summary'    => $this->queryInventorySummary($params),
            'product_history'      => $this->queryProductHistory($params),
            default                => null,
        };
    }

    // ========================================================================
    // Predefined Safe Queries (adapted to WMS PM Room schema)
    // ========================================================================

    /**
     * Check stock by part name, part number, or serial number.
     */
    private function queryStockCheck(array $params): array
    {
        $partName   = $params['part_name'] ?? '';
        $partNumber = $params['part_number'] ?? '';
        $clientName = $params['client_name'] ?? '';

        $query = DB::table('inventory')
            ->leftJoin('product', 'inventory.product_id', '=', 'product.id')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->leftJoin('storage_area', 'storage_bin.storage_area_id', '=', 'storage_area.id')
            ->leftJoin('storage_rak', 'storage_bin.storage_rak_id', '=', 'storage_rak.id')
            ->leftJoin('storage_lantai', 'storage_bin.storage_lantai_id', '=', 'storage_lantai.id')
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.qty',
                'inventory.condition',
                'inventory.status',
                'client.name as client_name',
                'storage_area.name as area',
                'storage_rak.name as rak',
                'storage_lantai.name as lantai',
                'storage_bin.name as bin'
            )
            ->where('inventory.qty', '>', 0);

        if ($partName) {
            $query->where('inventory.part_name', 'like', "%{$partName}%");
        }
        if ($partNumber) {
            $query->where('inventory.part_number', 'like', "%{$partNumber}%");
        }
        if ($clientName) {
            $query->where('client.name', 'like', "%{$clientName}%");
        }

        return $query->limit(50)->get()->toArray();
    }

    /**
     * Check aging inventory based on created_at date (since no last_movement_date exists).
     */
    private function queryAgingCheck(array $params): array
    {
        $days       = intval($params['days'] ?? 90);
        $clientName = $params['client_name'] ?? '';
        $cutoffDate = now()->subDays($days)->format('Y-m-d');

        $query = DB::table('inventory')
            ->leftJoin('product', 'inventory.product_id', '=', 'product.id')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->leftJoin('storage_area', 'storage_bin.storage_area_id', '=', 'storage_area.id')
            ->leftJoin('storage_rak', 'storage_bin.storage_rak_id', '=', 'storage_rak.id')
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.qty',
                'inventory.condition',
                'inventory.status',
                'inventory.created_at',
                'client.name as client_name',
                'storage_area.name as area',
                'storage_rak.name as rak',
                DB::raw('DATEDIFF(NOW(), inventory.created_at) as age_days')
            )
            ->where('inventory.qty', '>', 0)
            ->whereDate('inventory.created_at', '<=', $cutoffDate);

        if ($clientName) {
            $query->where('client.name', 'like', "%{$clientName}%");
        }

        return $query->orderBy('age_days', 'desc')
            ->limit(50)
            ->get()
            ->toArray();
    }

    /**
     * Lookup a serial number across inventory, inbound, and outbound.
     */
    private function querySerialNumber(array $params): array
    {
        $sn = $params['serial_number'] ?? '';

        if (empty($sn)) {
            return [];
        }

        $results = [];

        // Search inventory
        $inventory = DB::table('inventory')
            ->leftJoin('product', 'inventory.product_id', '=', 'product.id')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->leftJoin('storage_area', 'storage_bin.storage_area_id', '=', 'storage_area.id')
            ->leftJoin('storage_rak', 'storage_bin.storage_rak_id', '=', 'storage_rak.id')
            ->leftJoin('storage_lantai', 'storage_bin.storage_lantai_id', '=', 'storage_lantai.id')
            ->where('inventory.serial_number', $sn)
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.qty',
                'inventory.condition',
                'inventory.status',
                'client.name as client_name',
                'storage_area.name as area',
                'storage_rak.name as rak',
                'storage_lantai.name as lantai',
                'storage_bin.name as bin'
            )
            ->first();

        if ($inventory) {
            $inventory->source = 'Inventory';
            $results[] = $inventory;
        }

        // Search inbound_detail
        $inbound = DB::table('inbound_detail')
            ->join('inbound', 'inbound_detail.inbound_id', '=', 'inbound.id')
            ->leftJoin('client', 'inbound.client_id', '=', 'client.id')
            ->where('inbound_detail.serial_number', $sn)
            ->select(
                'inbound.number as inbound_number',
                'inbound_detail.part_name',
                'inbound_detail.part_number',
                'inbound_detail.serial_number',
                'inbound_detail.condition',
                'inbound.received_at',
                'client.name as client_name',
                'inbound.status'
            )
            ->first();

        if ($inbound) {
            $inbound->source = 'Inbound';
            $results[] = $inbound;
        }

        // Search outbound_detail via inventory (outbound_detail references inventory_id)
        $outbound = DB::table('outbound_detail')
            ->join('inventory', 'outbound_detail.inventory_id', '=', 'inventory.id')
            ->join('outbound', 'outbound_detail.outbound_id', '=', 'outbound.id')
            ->leftJoin('client', 'outbound.client_id', '=', 'client.id')
            ->where('inventory.serial_number', $sn)
            ->select(
                'outbound.number as outbound_number',
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'outbound.delivery_date',
                'client.name as client_name',
                'outbound.type as outbound_type'
            )
            ->first();

        if ($outbound) {
            $outbound->source = 'Outbound';
            $results[] = $outbound;
        }

        // Check inventory history (via inventory join for serial_number)
        $history = DB::table('inventory_history')
            ->join('inventory', 'inventory_history.inventory_id', '=', 'inventory.id')
            ->where('inventory.serial_number', $sn)
            ->select(
                'inventory_history.type',
                'inventory_history.description',
                'inventory_history.created_at',
                'inventory.serial_number',
                'inventory.part_name',
                'inventory.part_number'
            )
            ->orderBy('inventory_history.created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        return [
            'current'  => $results,
            'history'  => $history,
            'searched' => $sn,
            'summary'  => [
                'found_inventory' => !is_null($inventory),
                'found_inbound'   => !is_null($inbound),
                'found_outbound'  => !is_null($outbound),
                'history_count'   => count($history),
            ],
        ];
    }

    /**
     * Check storage location status (items in a specific area/rak/lantai/bin).
     */
    private function queryLocationStatus(array $params): array
    {
        $area   = $params['area'] ?? '';
        $rak    = $params['rak'] ?? '';
        $lantai = $params['lantai'] ?? '';
        $bin    = $params['bin'] ?? '';

        $query = DB::table('inventory')
            ->leftJoin('product', 'inventory.product_id', '=', 'product.id')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->leftJoin('storage_area', 'storage_bin.storage_area_id', '=', 'storage_area.id')
            ->leftJoin('storage_rak', 'storage_bin.storage_rak_id', '=', 'storage_rak.id')
            ->leftJoin('storage_lantai', 'storage_bin.storage_lantai_id', '=', 'storage_lantai.id')
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.qty',
                'inventory.condition',
                'inventory.status',
                'client.name as client_name',
                'storage_area.name as area',
                'storage_rak.name as rak',
                'storage_lantai.name as lantai',
                'storage_bin.name as bin'
            )
            ->where('inventory.qty', '>', 0);

        if ($area) {
            $query->where('storage_area.name', 'like', "%{$area}%");
        }
        if ($rak) {
            $query->where('storage_rak.name', 'like', "%{$rak}%");
        }
        if ($lantai) {
            $query->where('storage_lantai.name', 'like', "%{$lantai}%");
        }
        if ($bin) {
            $query->where('storage_bin.name', 'like', "%{$bin}%");
        }

        return $query->limit(50)->get()->toArray();
    }

    /**
     * Check inbound/PO status.
     */
    private function queryInboundStatus(array $params): array
    {
        $inboundNumber = $params['inbound_number'] ?? '';
        $clientName    = $params['client_name'] ?? '';

        $query = DB::table('inbound')
            ->leftJoin('client', 'inbound.client_id', '=', 'client.id')
            ->select(
                'inbound.number',
                'inbound.inbound_type',
                'inbound.owner_status',
                'inbound.status',
                'inbound.quantity',
                'inbound.received_at',
                'inbound.site_location',
                'inbound.remarks',
                'client.name as client_name'
            );

        if ($inboundNumber) {
            $query->where('inbound.number', 'like', "%{$inboundNumber}%");
        }
        if ($clientName) {
            $query->where('client.name', 'like', "%{$clientName}%");
        }

        return $query->latest('inbound.created_at')->limit(20)->get()->toArray();
    }

    /**
     * Outbound summary by client for a given period.
     */
    private function queryOutboundSummary(array $params): array
    {
        $clientName = $params['client_name'] ?? '';
        $month      = $params['month'] ?? now()->month;
        $year       = $params['year'] ?? now()->year;

        $summary = DB::table('outbound')
            ->leftJoin('client', 'outbound.client_id', '=', 'client.id')
            ->whereMonth('outbound.delivery_date', $month)
            ->whereYear('outbound.delivery_date', $year);

        if ($clientName) {
            $summary->where('client.name', 'like', "%{$clientName}%");
        }

        $summaryResult = $summary->select(
                'client.name as client_name',
                DB::raw('COUNT(*) as total_outbound'),
                DB::raw('SUM(outbound.qty) as total_qty')
            )
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_outbound')
            ->limit(20)
            ->get()
            ->toArray();

        // Also get total for the period
        $periodTotal = DB::table('outbound')
            ->whereMonth('delivery_date', $month)
            ->whereYear('delivery_date', $year)
            ->select(
                DB::raw('COUNT(*) as total_outbound'),
                DB::raw('SUM(qty) as total_qty')
            )
            ->first();

        return [
            'period'    => "{$year}-{$month}",
            'total'     => $periodTotal,
            'by_client' => $summaryResult,
        ];
    }

    /**
     * Inventory summary — total stock, by client, by storage area, by condition.
     */
    private function queryInventorySummary(array $params): array
    {
        $condition = $params['condition'] ?? null;

        $totalStock = DB::table('inventory')
            ->where('qty', '>', 0)
            ->sum('qty');

        $totalItems = DB::table('inventory')
            ->where('qty', '>', 0)
            ->count();

        $byCondition = DB::table('inventory')
            ->where('qty', '>', 0)
            ->select(
                'condition',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(qty) as total_qty')
            )
            ->groupBy('condition')
            ->orderByDesc('total_qty')
            ->get()
            ->toArray();

        $byClient = DB::table('inventory')
            ->join('client', 'inventory.client_id', '=', 'client.id')
            ->where('inventory.qty', '>', 0)
            ->select(
                'client.name',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(inventory.qty) as total_qty')
            )
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->toArray();

        $byArea = DB::table('inventory')
            ->join('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->join('storage_area', 'storage_bin.storage_area_id', '=', 'storage_area.id')
            ->where('inventory.qty', '>', 0)
            ->select(
                'storage_area.name as area',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(inventory.qty) as total_qty')
            )
            ->groupBy('storage_area.id', 'storage_area.name')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get()
            ->toArray();

        $byStatus = DB::table('inventory')
            ->where('qty', '>', 0)
            ->select(
                'status',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('SUM(qty) as total_qty')
            )
            ->groupBy('status')
            ->orderByDesc('total_qty')
            ->get()
            ->toArray();

        return [
            'total_stock'   => $totalStock,
            'total_items'   => $totalItems,
            'by_condition'  => $byCondition,
            'by_client'     => $byClient,
            'by_area'       => $byArea,
            'by_status'     => $byStatus,
        ];
    }

    /**
     * Product movement history by part name or serial number.
     */
    private function queryProductHistory(array $params): array
    {
        $partName     = $params['part_name'] ?? '';
        $serialNumber = $params['serial_number'] ?? '';

        if (empty($partName) && empty($serialNumber)) {
            return [];
        }

        $query = DB::table('inventory_history')
            ->leftJoin('inventory', 'inventory_history.inventory_id', '=', 'inventory.id')
            ->select(
                'inventory_history.type',
                'inventory_history.description',
                'inventory_history.created_at',
                'inventory.serial_number',
                'inventory.part_name',
                'inventory.part_number'
            )
            ->orderBy('inventory_history.created_at', 'desc')
            ->limit(50);

        if ($serialNumber) {
            $query->where('inventory.serial_number', 'like', "%{$serialNumber}%");
        }
        if ($partName) {
            $query->where('inventory.part_name', 'like', "%{$partName}%");
        }

        return $query->get()->toArray();
    }

    // ========================================================================
    // Reply Formatting via DeepSeek
    // ========================================================================

    private function generateGeneralChatReply(string $userMessage, ChatConversation $conversation): string
    {
        // If no API key is configured, return fallback immediately
        if (empty($this->apiKey)) {
            return 'Maaf, API key DeepSeek belum dikonfigurasi. Silakan isi DEEPSEEK_API_KEY di file .env';
        }

        $systemPrompt = $this->getGeneralChatPrompt();

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl . '/v1/chat/completions', [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 1000,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                // Guard against empty/null responses
                if (!empty(trim($content ?? ''))) {
                    return $content;
                }
                Log::warning('DeepSeek general chat returned empty content');
            }
        } catch (\Exception $e) {
            Log::error('DeepSeek general chat error: ' . $e->getMessage());
        }

        return 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.';
    }

    private function formatDataReply(
        string $userMessage,
        string $intent,
        array $params,
        array $data,
        ChatConversation $conversation
    ): string {
        // If no API key, skip the AI call and use fallback directly
        if (empty($this->apiKey)) {
            return $this->formatDataFallback($intent, $params, $data);
        }

        $systemPrompt = $this->getDataFormattingPrompt($intent);

        $dataContext = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Smart truncation: prioritize current data over history
        if (mb_strlen($dataContext) > 6000) {
            // Try to truncate only the history part if it exists
            if (isset($data['history']) && is_array($data['history'])) {
                $data['history'] = array_slice($data['history'], 0, 5);
                $dataContext = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            // If still too long, hard truncate
            if (mb_strlen($dataContext) > 6000) {
                $dataContext = mb_substr($dataContext, 0, 6000) . "\n... (data terpotong, tampilkan yang tersedia saja)";
            }
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl . '/v1/chat/completions', [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Pertanyaan user: \"{$userMessage}\"\n\nData hasil query:\n{$dataContext}\n\nTolong jawab pertanyaan user berdasarkan data di atas dalam Bahasa Indonesia yang natural dan mudah dipahami. TAMPILKAN SEMUA data yang tersedia — inbound, outbound, inventory, dan history. Jika ada bagian yang kosong/tidak ditemukan, sebutkan dengan jelas (misal: 'Data outbound tidak ditemukan untuk SN ini')."],
                    ],
                    'temperature' => 0.5,
                    'max_tokens'  => 4000,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                // Guard against empty/null responses — fall back to structured data
                if (!empty(trim($content ?? ''))) {
                    return $content;
                }
                Log::warning('DeepSeek data formatting returned empty content, using fallback');
            }
        } catch (\Exception $e) {
            Log::error('DeepSeek data formatting error: ' . $e->getMessage());
        }

        // Fallback: format data as simple text
        return $this->formatDataFallback($intent, $params, $data);
    }

    private function formatEmptyResult(string $intent, array $params): string
    {
        return match ($intent) {
            'stock_check'          => "❌ Tidak ditemukan stok untuk part name / part number yang dicari. Coba periksa kembali nama atau nomor part-nya ya.",
            'aging_check'          => "✅ Tidak ada produk aging yang ditemukan dengan kriteria tersebut.",
            'serial_number_lookup' => "❌ Serial number \"" . ($params['serial_number'] ?? '') . "\" tidak ditemukan di sistem kami. Coba periksa kembali nomornya.",
            'location_status'      => "❌ Tidak ditemukan item di lokasi tersebut.",
            'inbound_status'       => "❌ Inbound \"" . ($params['inbound_number'] ?? '') . "\" tidak ditemukan.",
            'outbound_summary'     => "❌ Tidak ada data outbound untuk periode tersebut.",
            'product_history'      => "❌ Tidak ada riwayat pergerakan untuk part / serial number tersebut.",
            'inventory_summary'    => "❌ Tidak dapat mengambil ringkasan inventori saat ini.",
            default                => "❌ Tidak ada data yang ditemukan untuk permintaan ini.",
        };
    }

    private function formatDataFallback(string $intent, array $params, array $data): string
    {
        // For serial_number_lookup, build a structured fallback response
        if ($intent === 'serial_number_lookup' && isset($data['current'])) {
            $sn = $data['searched'] ?? ($params['serial_number'] ?? '');
            $parts = ["📊 **Hasil pencarian untuk SN: {$sn}**\n"];

            foreach ($data['current'] as $item) {
                $source = $item->source ?? 'Unknown';
                $parts[] = "---";
                $parts[] = "**📍 Sumber: {$source}**";
                foreach ($item as $key => $value) {
                    if ($key !== 'source' && !empty($value)) {
                        $label = ucwords(str_replace('_', ' ', $key));
                        $parts[] = "- {$label}: {$value}";
                    }
                }
            }

            if (empty($data['current'])) {
                $parts[] = "❌ Tidak ditemukan di inventory, inbound, maupun outbound.";
            }

            // Check what's missing
            $summary = $data['summary'] ?? [];
            if (!empty($summary)) {
                $missing = [];
                if (empty($summary['found_inbound'])) $missing[] = 'Inbound';
                if (empty($summary['found_outbound'])) $missing[] = 'Outbound';
                if (empty($summary['found_inventory'])) $missing[] = 'Inventory';
                if (!empty($missing)) {
                    $parts[] = "\n⚠️ Data tidak ditemukan di: " . implode(', ', $missing);
                }
            }

            if (!empty($data['history'])) {
                $parts[] = "\n📜 **Riwayat ({$summary['history_count']} record):**";
                foreach ($data['history'] as $h) {
                    $h = (array) $h;
                    $parts[] = "- [{$h['created_at']}] {$h['type']} — {$h['description']}";
                }
            }

            return implode("\n", $parts);
        }

        $count = is_array($data) ? count($data) : 0;
        // Show more data in fallback (up to 10 items)
        $preview = json_encode(
            is_array($data) ? array_slice($data, 0, 10) : $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return "📊 Ditemukan **{$count}** hasil:\n```json\n{$preview}\n```";
    }

    // ========================================================================
    // System Prompts
    // ========================================================================

    private function getIntentClassificationPrompt(): string
    {
        return <<<PROMPT
Kamu adalah sistem klasifikasi intent untuk Warehouse Management System (WMS) Trans Kargo Solusindo — PM Room (Parts Management).
Tugasmu: klasifikasikan pertanyaan user ke dalam salah satu intent berikut dan ekstrak parameternya.

Intent yang tersedia:
1. stock_check - Cek stok spare part. Params: part_name, part_number, client_name
2. aging_check - Cek produk yang lama tidak bergerak (aging). Params: days (default 90), client_name
3. serial_number_lookup - Cari serial number di seluruh sistem. Params: serial_number
4. location_status - Cek status lokasi penyimpanan (area/rak/lantai/bin). Params: area, rak, lantai, bin
5. inbound_status - Cek status inbound/PO. Params: inbound_number, client_name
6. outbound_summary - Ringkasan outbound per periode. Params: client_name, month, year
7. inventory_summary - Ringkasan inventori keseluruhan. Params: condition
8. product_history - Riwayat pergerakan spare part. Params: part_name, serial_number
9. general_chat - Pertanyaan umum/FAQ/sapaan. Params: []

PENTING — ATURAN FOLLOW-UP / KONTEKS:
- Kamu akan menerima riwayat percakapan sebelumnya. GUNAKAN riwayat tersebut untuk memahami konteks.
- Jika pesan user saat ini adalah follow-up dari pertanyaan sebelumnya (contoh: "outboundnya mana?", "data lengkapnya?", "historynya dong?", "coba cek lagi", "tampilkan semua"), TETAP gunakan intent yang SAMA dengan pertanyaan sebelumnya dan EKSTRAK parameter (seperti serial_number, part_name, dll) dari riwayat percakapan.
- Jika pesan user menyebutkan "SN ini", "serial number ini", "part ini", atau kata ganti penunjuk, ambil nilai serial_number/part_number dari pesan user sebelumnya di riwayat.
- JANGAN jatuh ke general_chat hanya karena pesan saat ini pendek atau tidak mengandung parameter lengkap — cek dulu riwayat percakapan.

Di akhir response, berikan JSON dengan format:
{"intent": "nama_intent", "params": {"key": "value"}}

JANGAN tambahkan teks apapun selain JSON di atas.
PROMPT;
    }

    private function getGeneralChatPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant untuk Warehouse Management System Trans Kargo Solusindo (WMS PM Room — Parts Management).
Nama kamu: "TKS AI Assistant".
Kamu membantu user dengan pertanyaan seputar gudang spare part, inventori, dan penggunaan aplikasi WMS.

Konteks sistem:
- Aplikasi WMS untuk manajemen gudang spare part (PM Room / Parts Management)
- Modul: Inbound (Receiving/Purchase Order, Put Away), Inventory (List, History, Storage, Stock Movement, Cycle Count), Outbound, Return To Client, Back To WH, Asset Lifecycle
- Fitur utama: manajemen stok spare part, tracking serial number, produk aging, cycle count, transfer lokasi, asset lifecycle management
- Data master: Client, Product (part name/number), Storage (Area/Rak/Lantai/Bin), PIC, User
- User role: Admin WMS

Batasan:
- Jawab dalam Bahasa Indonesia yang ramah dan profesional
- Jika user bertanya data spesifik (stok, SN, lokasi, inbound, outbound), arahkan untuk bertanya dengan detail
- Jangan mengarang data — cukup bilang "saya perlu mencarikan datanya dulu" jika tidak yakin
- Jawaban singkat dan to the point, maksimal 2-3 paragraf

Kamu hanya menjawab pertanyaan terkait warehouse/gudang spare part dan sistem WMS.
PROMPT;
    }

    private function getDataFormattingPrompt(string $intent): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant untuk Warehouse Management System Trans Kargo Solusindo (WMS PM Room — Parts Management).
Tugasmu: ubah data query menjadi jawaban natural dalam Bahasa Indonesia.

Aturan:
1. Jawab dalam Bahasa Indonesia yang ramah, natural, dan mudah dipahami
2. Gunakan format yang rapi: sebutkan angka, nama, dan detail penting
3. Jika data banyak, rangkum poin-poin utamanya saja (maks 5-7 poin)
4. Gunakan emoji secukupnya untuk memperjelas (📦 stok, ⚠️ aging, 🔍 SN, 📋 inbound, 🚚 outbound, 📍 lokasi)
5. Jika ada data yang perlu perhatian khusus (aging > 90 hari, stok kosong, status pending), highlight
6. Akhiri dengan tawaran bantuan jika relevan ("Ada yang bisa saya bantu lagi?")

Jangan menyebutkan "data query" atau istilah teknis database dalam jawabanmu.
PROMPT;
    }

    // ========================================================================
    // Helpers
    // ========================================================================

    private function getRecentContext(ChatConversation $conversation, int $limit = 6): array
    {
        $messages = $conversation->messages()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->reverse();

        $context = [];
        foreach ($messages as $msg) {
            $content = $msg->content;
            // Truncate assistant messages to keep context clean and focused
            if ($msg->role === 'assistant' && mb_strlen($content) > 250) {
                $content = mb_substr($content, 0, 250) . '...';
            }
            $context[] = [
                'role'    => $msg->role,
                'content' => $content,
            ];
        }

        return $context;
    }

    private function parseJsonResponse(string $content): array
    {
        // Clean markdown code blocks if present
        $content = trim($content);
        $content = preg_replace('/^```(?:json)?\s*\n?/', '', $content);
        $content = preg_replace('/\n?```$/', '', $content);

        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $intent = $decoded['intent'] ?? 'general_chat';
            $params = $decoded['params'] ?? [];

            // Validate intent
            if (!in_array($intent, self::INTENTS, true)) {
                $intent = 'general_chat';
            }

            return ['intent' => $intent, 'params' => $params];
        }

        // Fallback: treat as general chat
        return ['intent' => 'general_chat', 'params' => []];
    }
}
