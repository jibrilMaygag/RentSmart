<?php
/**
 * Message Controller
 */

class MessageController extends BaseController
{
    public function showContactLandlord(int $propertyId): void
    {
        $this->requireAuth();
        $user = $this->user();

        if (!$user || $user['role'] !== 'renter') {
            $this->flash('error', 'Only renter accounts can send inquiries.');
            $this->redirect('dashboard');
        }

        $propertyModel = new Property();
        $property = $propertyModel->getWithDetails($propertyId);

        if (!$property) {
            http_response_code(404);
            $this->view('404', ['user' => $user]);
            return;
        }

        $old = $_SESSION['inquiry_old_input'] ?? [];
        $fieldErrors = $_SESSION['inquiry_form_errors'] ?? [];
        unset($_SESSION['inquiry_old_input'], $_SESSION['inquiry_form_errors']);

        $this->view('messages/contact-landlord', [
            'user' => $user,
            'property' => $property,
            'old' => $old,
            'fieldErrors' => $fieldErrors,
        ]);
    }

    public function sendInquiry(int $propertyId): void
    {
        $this->requireAuth();
        $user = $this->user();

        if (!$user || $user['role'] !== 'renter') {
            $this->flash('error', 'Only renter accounts can send inquiries.');
            $this->redirect('dashboard');
        }

        $propertyModel = new Property();
        $property = $propertyModel->getWithDetails($propertyId);

        if (!$property) {
            http_response_code(404);
            $this->view('404', ['user' => $user]);
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('message', 'Message');

        $fieldErrors = $validator->errors();
        $message = trim(html_entity_decode((string)$validator->get('message', ''), ENT_QUOTES, 'UTF-8'));
        $phone = trim(html_entity_decode((string)$validator->get('phone', ''), ENT_QUOTES, 'UTF-8'));

        if (strlen($message) < 10) {
            $fieldErrors['message'][] = 'Message must be at least 10 characters.';
        }

        $_SESSION['inquiry_old_input'] = [
            'phone' => $phone,
            'message' => $message,
        ];

        if (!empty($fieldErrors)) {
            $_SESSION['inquiry_form_errors'] = $fieldErrors;
            $this->flash('errors', $fieldErrors);
            $this->redirect('property/' . $propertyId . '/contact');
        }

        try {
            $messageModel = new Message();
            $messageModel->createPropertyInquiry(
                (int)$user['id'],
                (int)$property['landlord_id'],
                $property,
                $message,
                $phone
            );

            if ($phone !== '' && ($user['phone'] ?? '') !== $phone) {
                $userModel = new User();
                $userModel->update((int)$user['id'], ['phone' => $phone]);
            }

            unset($_SESSION['inquiry_old_input'], $_SESSION['inquiry_form_errors']);
            $this->flash('success', 'Your inquiry has been sent to the landlord.');
            $this->redirect('property/' . $propertyId);
        } catch (Throwable $e) {
            error_log('Inquiry error: ' . $e->getMessage());
            $this->flash('error', 'We could not send your message right now. Please try again.');
            $this->redirect('property/' . $propertyId . '/contact');
        }
    }
}
