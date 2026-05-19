<?php
/**
 * RentSmart – Database setup & seed script
 * Run once: php database/setup.php
 */

require_once __DIR__ . '/../config/bootstrap.php';

$db = Database::getInstance()->getConnection();

echo "Setting up RentSmart database...\n";

$sql = file_get_contents(__DIR__ . '/schema.sql');

// Split statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    fn($s) => !empty($s)
);

$db->exec('SET FOREIGN_KEY_CHECKS=0');
foreach ($statements as $stmt) {
    try {
        $db->exec($stmt);
    } catch (PDOException $e) {
        echo "Warning: " . $e->getMessage() . "\n";
    }
}
$db->exec('SET FOREIGN_KEY_CHECKS=1');

// ── Seed sample data if tables are empty ──────────────────────────────────────

$count = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();

if ($count === 0) {
    echo "Seeding sample data...\n";

    // Admin user
    $db->exec("INSERT INTO users (full_name, email, password, role) VALUES
        ('Admin User',       'admin@rentsmart.com',    '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'),
        ('Demo Landlord',    'landlord@rentsmart.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'landlord'),
        ('Demo Renter',      'renter@rentsmart.com',   '" . password_hash('password', PASSWORD_DEFAULT) . "', 'renter')
    ");

    // Sample amenities
    $db->exec("INSERT INTO amenities (name, icon) VALUES
        ('Parking',    'fa-car'),
        ('Gym',        'fa-dumbbell'),
        ('Security',   'fa-shield-alt'),
        ('WiFi',       'fa-wifi'),
        ('Generator',  'fa-bolt'),
        ('Balcony',    'fa-door-open'),
        ('Garden',     'fa-leaf'),
        ('Pool',       'fa-swimming-pool'),
        ('Furnished',  'fa-couch'),
        ('Pet Friendly','fa-paw')
    ");

    // Sample properties (landlord_id = 2)
    $props = [
        ['Modern Apartment in Bole',         30200, 'rent', 'apartment', 2, 2, 120,  'Addis Ababa', 'Bole',      1, 'Experience luxury living in this stunning modern apartment located in the heart of Bole. Features floor-to-ceiling windows offering breathtaking city views, a state-of-the-art kitchen, and spacious bedrooms.', 'pexels-photo-106399.jpeg'],
        ['Luxury Villa in Ayat',             80000, 'rent', 'villa',     4, 3, 280,  'Addis Ababa', 'Ayat',      1, 'Stunning luxury villa with private garden, security, and modern finishes. Perfect for families seeking space and comfort in a prestigious location.', 'pexels-photo-164558.jpeg'],
        ['Cozy Studio in Kazanchis',         30000, 'rent', 'studio',    1, 1, 60,   'Addis Ababa', 'Kazanchis', 0, 'A well-maintained cozy studio apartment perfect for a single professional. Fully furnished and includes high-speed WiFi.', 'pexels-photo-271624.jpeg'],
        ['Family Home in Sar Bet',           20600, 'rent', 'house',     3, 2, 180,  'Addis Ababa', 'Sar Bet',   1, 'Spacious family home with a backyard, parking, and quiet neighborhood. Close to schools and amenities.', 'pexels-photo-323780_1.jpeg'],
        ['Spacious Family House',            22000, 'rent', 'house',     3, 2, 180,  'Addis Ababa', 'Sar Bet',   0, 'Well-built family house with security and ample parking. Close to international school and shopping.', 'pexels-photo-1396122.jpeg'],
        ['Downtown Loft in Piazza',          15500, 'rent', 'apartment', 1, 1, 85,   'Addis Ababa', 'Piazza',    0, 'Trendy loft in the heart of downtown with city views and modern design. Walking distance to restaurants and shops.', 'pexels-photo-1080721.jpeg'],
        ['Luxury Penthouse in Bole',         45000, 'rent', 'apartment', 3, 3, 220,  'Addis Ababa', 'Bole',      1, 'Top-floor penthouse with wraparound terrace, concierge service, gym access, and breathtaking views of Addis Ababa.', 'pexels-photo-279746.jpeg'],
        ['Cozy Apartment in Gotera',         18000, 'rent', 'apartment', 2, 1, 95,   'Addis Ababa', 'Gotera',    0, 'Comfortable apartment in a quiet residential area near parks. Great for couples or small families.', 'pexels-photo-1571460.jpeg'],
        ['Executive Villa for Sale',        3500000,'sale', 'villa',     5, 4, 450,  'Addis Ababa', 'CMC',       1, 'Magnificent executive villa on a generous plot with landscaped garden, swimming pool, and 3-car garage.', 'pexels-photo-259962.jpeg'],
        ['Office Space in Kazanchis',        25000, 'rent', 'office',    0, 2, 150,  'Addis Ababa', 'Kazanchis', 0, 'Modern commercial office space in a prime business district with backup power, fast internet, and reception services.', 'pexels-photo-259962_1.jpeg'],
    ];

    $stmt = $db->prepare(
        'INSERT INTO properties (landlord_id,title,price,listing_type,property_type,bedrooms,bathrooms,area_sqm,city,sub_city,is_featured,description,address,status)
         VALUES (2,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );

    foreach ($props as $idx => $p) {
        $stmt->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],$p[7],$p[8],$p[9],$p[10],'Kebele ' . ($idx+1) . ', ' . $p[8],'available']);
        $propId = (int)$db->lastInsertId();

        // Primary image
        $db->prepare('INSERT INTO property_images (property_id,image_path,is_primary,sort_order) VALUES (?,?,1,0)')
           ->execute([$propId, 'assets/media/img/' . $p[11]]);

        // Attach 2-3 amenities
        $amenityIds = array_slice([1,2,3,4,5,6,7,8,9,10], $idx % 5, 3);
        $stmtAm = $db->prepare('INSERT IGNORE INTO property_amenities (property_id, amenity_id) VALUES (?,?)');
        foreach ($amenityIds as $aid) {
            $stmtAm->execute([$propId, $aid]);
        }
    }

    echo "Seeded: 3 users, " . count($props) . " properties, 10 amenities.\n";
    echo "\nDemo Credentials:\n";
    echo "  Landlord: landlord@rentsmart.com / password\n";
    echo "  Renter:   renter@rentsmart.com   / password\n";
}

echo "\nSetup complete!\n";
