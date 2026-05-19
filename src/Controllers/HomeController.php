<?php
/**
 * Home Controller
 */

class HomeController extends BaseController
{
    public function index(): void
    {
        $propertyModel = new Property();
        $featured   = $propertyModel->getFeatured(8);
        $cities     = $propertyModel->getCities();
        $totalProps = $propertyModel->getTotalCount();

        $this->view('home', [
            'featured'   => $featured,
            'cities'     => $cities,
            'totalProps' => $totalProps,
            'user'       => $this->user(),
        ]);
    }

    public function search(): void
    {
        $propertyModel = new Property();

        $filters = [
            'keyword'       => trim($_GET['keyword']       ?? ''),
            'listing_type'  => $_GET['listing_type']       ?? 'rent',
            'property_type' => $_GET['property_type']      ?? '',
            'city'          => trim($_GET['city']          ?? ''),
            'min_price'     => $_GET['min_price']          ?? '',
            'max_price'     => $_GET['max_price']          ?? '',
            'bedrooms'      => $_GET['bedrooms']           ?? '',
            'limit'         => ITEMS_PER_PAGE,
        ];

        $properties = $propertyModel->search($filters);
        $cities     = $propertyModel->getCities();

        $this->view('search-results', [
            'properties' => $properties,
            'cities'     => $cities,
            'filters'    => $filters,
            'user'       => $this->user(),
        ]);
    }

    public function showProperty(int $id): void
    {
        $propertyModel = new Property();
        $property = $propertyModel->getWithDetails($id);

        if (!$property) {
            http_response_code(404);
            $this->view('404');
            exit;
        }

        $propertyModel->incrementViews($id);
        $property['views'] = (int)($property['views'] ?? 0) + 1;

        // Check if current user has favorited this property
        $isFavorited = false;
        $user = $this->user();
        if ($user) {
            $isFavorited = $propertyModel->isFavorited($id, $user['id']);
        }

        $this->view('property-detail', [
            'property'    => $property,
            'user'        => $user,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function dashboard(): void
    {
        $this->requireAuth();
        $user          = $this->user();
        $propertyModel = new Property();
        $myProperties  = [];

        if ($user && $user['role'] === 'landlord') {
            $userModel    = new User();
            $myProperties = $userModel->getLandlordProperties($user['id']);
        }

        $favorites = [];
        if ($user) {
            $favorites = $propertyModel->getUserFavorites($user['id']);
        }

        $this->view('dashboard', [
            'user'         => $user,
            'myProperties' => $myProperties,
            'favorites'    => $favorites,
        ]);
    }

    public function favoritesPage(): void
    {
        $this->requireAuth();
        $user = $this->user();

        if (!$user || $user['role'] !== 'renter') {
            $this->flash('error', 'Only renters can access saved properties.');
            $this->redirect('dashboard');
        }

        $propertyModel = new Property();
        $favorites = $propertyModel->getUserFavorites($user['id']);

        $this->view('renter/favorites', [
            'user' => $user,
            'favorites' => $favorites,
        ]);
    }

    public function showContact(): void
    {
        $old = $_SESSION['contact_old_input'] ?? [];
        unset($_SESSION['contact_old_input']);

        $this->view('contact', [
            'user' => $this->user(),
            'old'  => $old,
        ]);
    }

    public function submitContact(): void
    {
        $_SESSION['contact_old_input'] = [
            'name'    => trim($_POST['name'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''),
            'phone'   => trim($_POST['phone'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        $validator = new Validator($_POST);
        $validator->required('name',    'Name')
                  ->required('email',   'Email')
                  ->email('email',      'Email')
                  ->required('message', 'Message');

        if (!$validator->passes()) {
            $this->flash('errors', $validator->errors());
            $this->redirect('contact');
        }

        // Save to database
        try {
            $db = Database::getInstance();
            $db->query(
                'INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)',
                [
                    $validator->get('name'),
                    $validator->get('email'),
                    $validator->get('phone', ''),
                    $validator->get('message'),
                ]
            );
            unset($_SESSION['contact_old_input']);
            $this->flash('success', 'Your message has been sent! We\'ll be in touch soon.');
        } catch (Exception $e) {
            $this->flash('error', 'Failed to send message. Please try again.');
        }

        $this->redirect('contact');
    }

    public function toggleFavorite(int $propertyId): void
    {
        if (!$this->auth->check()) {
            $this->json(['error' => 'Not authenticated', 'redirect' => APP_URL . '/login'], 401);
        }

        $user          = $this->user();
        $propertyModel = new Property();
        $result        = $propertyModel->toggleFavorite($propertyId, $user['id']);

        $this->json(['favorited' => $result]);
    }
}
