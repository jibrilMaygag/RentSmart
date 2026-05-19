<?php
/**
 * Landlord Controller
 */

class LandlordController extends BaseController
{
    public function listings(): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();

        $filters = [
            'status' => $_GET['status'] ?? 'all',
            'keyword' => trim($_GET['q'] ?? ''),
        ];

        $listings = $propertyModel->getLandlordListings($user['id'], $filters);
        $counts = $propertyModel->getLandlordListingCounts($user['id']);

        $this->view('landlord/my-listings', [
            'user' => $user,
            'listings' => $listings,
            'counts' => $counts,
            'filters' => $filters,
        ]);
    }

    public function showCreateListing(): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();

        $old = $_SESSION['listing_old_input'] ?? [];
        $fieldErrors = $_SESSION['listing_form_errors'] ?? [];
        unset($_SESSION['listing_old_input'], $_SESSION['listing_form_errors']);

        $this->view('landlord/listing-form', [
            'user' => $user,
            'isEditing' => false,
            'listing' => null,
            'old' => $old,
            'fieldErrors' => $fieldErrors,
            'amenities' => $propertyModel->getAmenities(),
        ]);
    }

    public function createListing(): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();
        $uploadService = new UploadService();

        [$data, $fieldErrors] = $this->validateListingPayload(false);
        $_SESSION['listing_old_input'] = $this->persistListingOldInput($_POST);

        if (!empty($fieldErrors)) {
            $_SESSION['listing_form_errors'] = $fieldErrors;
            $this->flash('errors', $fieldErrors);
            $this->redirect('dashboard/listings/create');
        }

        $files = $this->normalizeUploadedFiles($_FILES['images'] ?? []);
        if (empty($files)) {
            $fieldErrors['images'][] = 'At least one property image is required.';
            $_SESSION['listing_form_errors'] = $fieldErrors;
            $this->flash('errors', $fieldErrors);
            $this->redirect('dashboard/listings/create');
        }

        $uploadedImagePaths = [];

        try {
            foreach ($files as $file) {
                $uploadedImagePaths[] = $uploadService->uploadPropertyImage($file);
            }

            $propertyModel->createForLandlord(
                $user['id'],
                $data,
                $this->sanitizeAmenityIds($_POST['amenities'] ?? []),
                $uploadedImagePaths
            );

            unset($_SESSION['listing_old_input'], $_SESSION['listing_form_errors']);
            $this->flash('success', 'Your listing has been published.');
            $this->redirect('dashboard/listings');
        } catch (Throwable $e) {
            foreach ($uploadedImagePaths as $imagePath) {
                $uploadService->delete($imagePath);
            }

            error_log('Create listing error: ' . $e->getMessage());
            $this->flash('error', $e->getMessage());
            $this->redirect('dashboard/listings/create');
        }
    }

    public function showEditListing(int $propertyId): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();
        $listing = $propertyModel->getOwnedWithDetails($propertyId, $user['id']);

        if (!$listing) {
            http_response_code(404);
            $this->view('404', ['user' => $user]);
            return;
        }

        $old = $_SESSION['listing_old_input'] ?? [];
        $fieldErrors = $_SESSION['listing_form_errors'] ?? [];
        unset($_SESSION['listing_old_input'], $_SESSION['listing_form_errors']);

        $this->view('landlord/listing-form', [
            'user' => $user,
            'isEditing' => true,
            'listing' => $listing,
            'old' => $old,
            'fieldErrors' => $fieldErrors,
            'amenities' => $propertyModel->getAmenities(),
        ]);
    }

    public function updateListing(int $propertyId): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();
        $uploadService = new UploadService();

        $listing = $propertyModel->getOwnedWithDetails($propertyId, $user['id']);
        if (!$listing) {
            http_response_code(404);
            $this->view('404', ['user' => $user]);
            return;
        }

        [$data, $fieldErrors] = $this->validateListingPayload(true);
        $_SESSION['listing_old_input'] = $this->persistListingOldInput($_POST);

        if (!empty($fieldErrors)) {
            $_SESSION['listing_form_errors'] = $fieldErrors;
            $this->flash('errors', $fieldErrors);
            $this->redirect('dashboard/listings/' . $propertyId . '/edit');
        }

        $uploadedImagePaths = [];

        try {
            foreach ($this->normalizeUploadedFiles($_FILES['images'] ?? []) as $file) {
                $uploadedImagePaths[] = $uploadService->uploadPropertyImage($file);
            }

            $propertyModel->updateForLandlord(
                $propertyId,
                $user['id'],
                $data,
                $this->sanitizeAmenityIds($_POST['amenities'] ?? []),
                $uploadedImagePaths
            );

            unset($_SESSION['listing_old_input'], $_SESSION['listing_form_errors']);
            $this->flash('success', 'Your listing has been updated.');
            $this->redirect('dashboard/listings/' . $propertyId . '/edit');
        } catch (Throwable $e) {
            foreach ($uploadedImagePaths as $imagePath) {
                $uploadService->delete($imagePath);
            }

            error_log('Update listing error: ' . $e->getMessage());
            $this->flash('error', $e->getMessage());
            $this->redirect('dashboard/listings/' . $propertyId . '/edit');
        }
    }

    public function updateListingStatus(int $propertyId): void
    {
        $user = $this->requireLandlordUser();
        $status = $_POST['status'] ?? '';
        $propertyModel = new Property();

        try {
            $propertyModel->updateStatusForLandlord($propertyId, $user['id'], $status);
            $this->flash('success', 'Listing status updated.');
        } catch (Throwable $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect('dashboard/listings');
    }

    public function deleteListing(int $propertyId): void
    {
        $user = $this->requireLandlordUser();
        $propertyModel = new Property();
        $uploadService = new UploadService();

        try {
            $imagePaths = $propertyModel->deleteForLandlord($propertyId, $user['id']);
            foreach ($imagePaths as $imagePath) {
                if ($uploadService->isManagedPropertyImage($imagePath)) {
                    $uploadService->delete($imagePath);
                }
            }

            $this->flash('success', 'Your listing has been removed.');
        } catch (Throwable $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect('dashboard/listings');
    }

    public function messages(): void
    {
        $user = $this->requireLandlordUser();
        $messageModel = new Message();
        $messages = $messageModel->getInboxForRecipient($user['id']);

        $selectedId = isset($_GET['message']) ? (int)$_GET['message'] : 0;
        $selectedMessage = null;

        if ($selectedId > 0) {
            $selectedMessage = $messageModel->getMessageForRecipient($selectedId, $user['id']);
        }

        if (!$selectedMessage && !empty($messages)) {
            $selectedMessage = $messages[0];
        }

        if ($selectedMessage && !(bool)($selectedMessage['is_read'] ?? false)) {
            $messageModel->markAsRead((int)$selectedMessage['id'], $user['id']);
            $selectedMessage['is_read'] = 1;
            foreach ($messages as &$message) {
                if ((int)$message['id'] === (int)$selectedMessage['id']) {
                    $message['is_read'] = 1;
                    break;
                }
            }
            unset($message);
        }

        $this->view('landlord/messages', [
            'user' => $user,
            'messages' => $messages,
            'selectedMessage' => $selectedMessage,
        ]);
    }

    private function requireLandlordUser(): array
    {
        $this->requireAuth();
        $user = $this->user();

        if (!$user || $user['role'] !== 'landlord') {
            $this->flash('error', 'This page is only available for landlord accounts.');
            $this->redirect('dashboard');
        }

        return $user;
    }

    private function validateListingPayload(bool $isEditing): array
    {
        $validator = new Validator($_POST);
        $validator->required('title', 'Title')
            ->required('description', 'Description')
            ->required('listing_type', 'Listing Type')
            ->required('property_type', 'Property Type')
            ->required('status', 'Status')
            ->required('price', 'Price')
            ->numeric('price', 'Price')
            ->required('address', 'Address')
            ->required('city', 'City')
            ->required('bedrooms', 'Bedrooms')
            ->numeric('bedrooms', 'Bedrooms')
            ->required('bathrooms', 'Bathrooms')
            ->numeric('bathrooms', 'Bathrooms');

        $validator->inArray('listing_type', ['rent', 'sale'], 'Listing Type')
            ->inArray('property_type', ['apartment', 'house', 'villa', 'studio', 'office', 'land'], 'Property Type')
            ->inArray('status', ['available', 'pending', 'rented', 'sold'], 'Status');

        if ($validator->get('area_sqm') !== '') {
            $validator->numeric('area_sqm', 'Area');
        }

        $fieldErrors = $validator->errors();

        $price = (float)$validator->get('price', 0);
        if ($price <= 0) {
            $fieldErrors['price'][] = 'Price must be greater than zero.';
        }

        foreach (['bedrooms', 'bathrooms'] as $field) {
            $value = (int)$validator->get($field, 0);
            if ($value < 0) {
                $fieldErrors[$field][] = ucfirst($field) . ' cannot be negative.';
            }
        }

        if ($validator->get('area_sqm') !== '' && (float)$validator->get('area_sqm') < 0) {
            $fieldErrors['area_sqm'][] = 'Area cannot be negative.';
        }

        $data = [
            'title' => html_entity_decode((string)$validator->get('title'), ENT_QUOTES, 'UTF-8'),
            'description' => html_entity_decode((string)$validator->get('description'), ENT_QUOTES, 'UTF-8'),
            'price' => $price,
            'listing_type' => $validator->get('listing_type', 'rent'),
            'property_type' => $validator->get('property_type', 'apartment'),
            'status' => $validator->get('status', 'available'),
            'bedrooms' => (int)$validator->get('bedrooms', 0),
            'bathrooms' => (int)$validator->get('bathrooms', 0),
            'area_sqm' => $validator->get('area_sqm') === '' ? null : (float)$validator->get('area_sqm'),
            'address' => html_entity_decode((string)$validator->get('address'), ENT_QUOTES, 'UTF-8'),
            'city' => html_entity_decode((string)$validator->get('city'), ENT_QUOTES, 'UTF-8'),
            'sub_city' => html_entity_decode((string)$validator->get('sub_city', ''), ENT_QUOTES, 'UTF-8'),
        ];

        return [$data, $fieldErrors];
    }

    private function sanitizeAmenityIds(array $amenityIds): array
    {
        return array_values(array_filter(
            array_map('intval', $amenityIds),
            static fn(int $id): bool => $id > 0
        ));
    }

    private function persistListingOldInput(array $input): array
    {
        return [
            'title' => trim($input['title'] ?? ''),
            'description' => trim($input['description'] ?? ''),
            'listing_type' => $input['listing_type'] ?? 'rent',
            'property_type' => $input['property_type'] ?? 'apartment',
            'status' => $input['status'] ?? 'available',
            'price' => trim($input['price'] ?? ''),
            'address' => trim($input['address'] ?? ''),
            'city' => trim($input['city'] ?? ''),
            'sub_city' => trim($input['sub_city'] ?? ''),
            'bedrooms' => trim($input['bedrooms'] ?? ''),
            'bathrooms' => trim($input['bathrooms'] ?? ''),
            'area_sqm' => trim($input['area_sqm'] ?? ''),
            'amenities' => isset($input['amenities']) && is_array($input['amenities']) ? $input['amenities'] : [],
        ];
    }

    private function normalizeUploadedFiles(array $files): array
    {
        if (empty($files) || !isset($files['name']) || !is_array($files['name'])) {
            return [];
        }

        $normalized = [];
        foreach ($files['name'] as $index => $name) {
            $error = $files['error'][$index] ?? UPLOAD_ERR_NO_FILE;
            if ($error === UPLOAD_ERR_NO_FILE || $name === '') {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'type' => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error' => $error,
                'size' => $files['size'][$index] ?? 0,
            ];
        }

        return $normalized;
    }
}
