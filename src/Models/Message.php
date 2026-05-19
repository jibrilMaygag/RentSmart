<?php
/**
 * Message Model
 */

class Message extends BaseModel
{
    protected string $table = 'messages';

    public function getInboxForRecipient(int $recipientId): array
    {
        $messages = $this->db->query(
            'SELECT m.*, sender.full_name AS sender_name, sender.email AS sender_email, sender.phone AS sender_phone
             FROM messages m
             JOIN users sender ON sender.id = m.sender_id
             WHERE m.recipient_id = ?
             ORDER BY m.is_read ASC, m.created_at DESC',
            [$recipientId]
        )->fetchAll();

        return array_map([$this, 'decorateMessage'], $messages);
    }

    public function getMessageForRecipient(int $messageId, int $recipientId): array|null
    {
        $message = $this->db->query(
            'SELECT m.*, sender.full_name AS sender_name, sender.email AS sender_email, sender.phone AS sender_phone
             FROM messages m
             JOIN users sender ON sender.id = m.sender_id
             WHERE m.id = ? AND m.recipient_id = ?
             LIMIT 1',
            [$messageId, $recipientId]
        )->fetch();

        return $message ? $this->decorateMessage($message) : null;
    }

    public function markAsRead(int $messageId, int $recipientId): void
    {
        $this->db->query(
            'UPDATE messages SET is_read = 1 WHERE id = ? AND recipient_id = ?',
            [$messageId, $recipientId]
        );
    }

    public function createPropertyInquiry(int $senderId, int $recipientId, array $property, string $body, string $phone = ''): string
    {
        $propertyId = (int)($property['id'] ?? 0);
        $propertyTitle = trim((string)($property['title'] ?? 'Property Inquiry'));

        $subject = $propertyId > 0
            ? "[#{$propertyId}] {$propertyTitle}"
            : $propertyTitle;

        $messageBody = trim($body);
        if ($phone !== '') {
            $messageBody = "Phone: {$phone}\n\n" . $messageBody;
        }

        return $this->insert([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'body' => $messageBody,
        ]);
    }

    private function decorateMessage(array $message): array
    {
        $subject = trim((string)($message['subject'] ?? ''));
        $body = trim((string)($message['body'] ?? ''));

        $message['property_id'] = null;
        $message['property_title'] = $subject;

        if (preg_match('/^\[#(\d+)\]\s*(.+)$/', $subject, $matches)) {
            $message['property_id'] = (int)$matches[1];
            $message['property_title'] = trim($matches[2]);
        }

        $preview = preg_replace('/\s+/', ' ', $body) ?? '';
        $message['preview'] = function_exists('mb_strimwidth')
            ? mb_strimwidth($preview, 0, 120, '...')
            : (strlen($preview) > 120 ? substr($preview, 0, 117) . '...' : $preview);

        return $message;
    }
}
