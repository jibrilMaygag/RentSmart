<?php
/**
 * RentSmart - Database setup and presentation seed script
 *
 * Usage:
 *   php database/setup.php
 *   php database/setup.php --fresh-demo
 */

require_once __DIR__ . '/../config/bootstrap.php';

$db = Database::getInstance()->getConnection();
$refreshDemo = in_array('--fresh-demo', $argv ?? [], true);

echo "Setting up RentSmart database...\n";

$sql = file_get_contents(__DIR__ . '/schema.sql');
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    static fn($statement) => $statement !== ''
);

$db->exec('SET FOREIGN_KEY_CHECKS=0');
foreach ($statements as $statement) {
    try {
        $db->exec($statement);
    } catch (PDOException $e) {
        echo "Warning: " . $e->getMessage() . "\n";
    }
}
$db->exec('SET FOREIGN_KEY_CHECKS=1');

$amenities = [
    ['24/7 Security', 'shield'],
    ['Dedicated Parking', 'local_parking'],
    ['Backup Generator', 'bolt'],
    ['High-Speed Wi-Fi', 'wifi'],
    ['Elevator Access', 'elevator'],
    ['Private Balcony', 'balcony'],
    ['Furnished', 'weekend'],
    ['Laundry Room', 'local_laundry_service'],
    ['Water Storage', 'water_drop'],
    ['Gym Access', 'fitness_center'],
    ['Private Garden', 'yard'],
    ['En-suite Bedrooms', 'king_bed'],
];

$demoUsers = [
    ['full_name' => 'Admin User', 'email' => 'admin@rentsmart.com', 'password' => 'admin123', 'role' => 'admin', 'phone' => '+251911000001'],
    ['full_name' => 'Samuel Bekele', 'email' => 'samuel.bekele@rentsmart.com', 'password' => 'password', 'role' => 'landlord', 'phone' => '+251911245678'],
    ['full_name' => 'Hana Alemu', 'email' => 'hana.alemu@rentsmart.com', 'password' => 'password', 'role' => 'landlord', 'phone' => '+251911564321'],
    ['full_name' => 'Ruth Mekonnen', 'email' => 'ruth.mekonnen@rentsmart.com', 'password' => 'password', 'role' => 'renter', 'phone' => '+251912334455'],
    ['full_name' => 'Daniel Tadesse', 'email' => 'daniel.tadesse@rentsmart.com', 'password' => 'password', 'role' => 'renter', 'phone' => '+251913667788'],
];

$presentationImages = [
    'bedroom' => 'https://images.unsplash.com/photo-1558942548-89bf85600e32?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'open_plan' => 'https://images.unsplash.com/photo-1741764014072-68953e93cd48?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'living_room' => 'https://images.unsplash.com/photo-1648475236583-2e25a6cbf3bd?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'aerial' => 'https://images.unsplash.com/photo-1723641881214-75af0b43fa0b?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'kitchen' => 'https://images.unsplash.com/photo-1721395283507-1b17e527a922?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'exterior_balconies' => 'https://images.unsplash.com/photo-1774979517612-db480ae5b587?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'exterior_entry' => 'https://images.unsplash.com/photo-1768638687896-35bde623d532?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'addis_lake' => 'https://images.unsplash.com/photo-1724001079027-800ed9a8ee4d?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'addis_dusk' => 'https://images.unsplash.com/photo-1771495562804-373fb516114c?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'addis_street' => 'https://images.unsplash.com/photo-1771495604392-2008757fb32a?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
];

$demoProperties = [
    [
        'landlord_email' => 'samuel.bekele@rentsmart.com',
        'title' => 'Bole Vista Residence',
        'description' => 'Bright, high-floor apartment with a generous living room, full-height windows, and a well-planned kitchen. The building sits minutes from cafes, offices, and everyday essentials in Bole, making it a strong fit for professionals and small families who want a polished city address.',
        'price' => 78000,
        'listing_type' => 'rent',
        'property_type' => 'apartment',
        'status' => 'available',
        'bedrooms' => 3,
        'bathrooms' => 2,
        'area_sqm' => 182,
        'address' => 'Cameroon Street, near Friendship Square',
        'city' => 'Addis Ababa',
        'sub_city' => 'Bole',
        'is_featured' => 1,
        'images' => [$presentationImages['exterior_balconies'], $presentationImages['open_plan'], $presentationImages['living_room']],
        'amenities' => ['24/7 Security', 'Dedicated Parking', 'Backup Generator', 'High-Speed Wi-Fi', 'Elevator Access', 'Private Balcony'],
    ],
    [
        'landlord_email' => 'samuel.bekele@rentsmart.com',
        'title' => 'Ayat Courtyard Villa',
        'description' => 'Spacious family villa with a landscaped compound, multiple sitting areas, and strong natural light throughout the home. It offers the privacy and room expected in Ayat while staying practical for daily living, home entertaining, and long-term family use.',
        'price' => 145000,
        'listing_type' => 'rent',
        'property_type' => 'villa',
        'status' => 'available',
        'bedrooms' => 5,
        'bathrooms' => 4,
        'area_sqm' => 360,
        'address' => 'Ayat Zone 5, close to the ring road access',
        'city' => 'Addis Ababa',
        'sub_city' => 'Ayat',
        'is_featured' => 1,
        'images' => [$presentationImages['exterior_entry'], $presentationImages['bedroom'], $presentationImages['kitchen']],
        'amenities' => ['24/7 Security', 'Private Garden', 'Dedicated Parking', 'Water Storage', 'En-suite Bedrooms', 'Laundry Room'],
    ],
    [
        'landlord_email' => 'hana.alemu@rentsmart.com',
        'title' => 'Lakeview Apartment near Haile Resort',
        'description' => 'Well-finished two-bedroom apartment with a calm residential feel and easy access to the lakefront side of Hawassa. The layout is efficient, the finishes are clean, and the home works especially well for couples, young families, or remote professionals looking for a quieter setting.',
        'price' => 36000,
        'listing_type' => 'rent',
        'property_type' => 'apartment',
        'status' => 'available',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'area_sqm' => 118,
        'address' => 'Tabor neighborhood, short drive from the lakeside',
        'city' => 'Hawassa',
        'sub_city' => 'Tabor',
        'is_featured' => 0,
        'images' => [$presentationImages['addis_lake'], $presentationImages['open_plan'], $presentationImages['aerial']],
        'amenities' => ['Dedicated Parking', 'High-Speed Wi-Fi', 'Private Balcony', 'Laundry Room'],
    ],
    [
        'landlord_email' => 'hana.alemu@rentsmart.com',
        'title' => 'Riverside Villa in Bahir Dar',
        'description' => 'A premium sale opportunity with generous indoor-outdoor flow, large reception areas, and a mature compound that suits private residence use or a high-end family home. The property stands out for its scale, curb appeal, and easy access to the city’s more established residential pockets.',
        'price' => 9800000,
        'listing_type' => 'sale',
        'property_type' => 'villa',
        'status' => 'available',
        'bedrooms' => 5,
        'bathrooms' => 4,
        'area_sqm' => 430,
        'address' => 'Kebele 14, close to the lakeside corridor',
        'city' => 'Bahir Dar',
        'sub_city' => 'Kebele 14',
        'is_featured' => 1,
        'images' => [$presentationImages['addis_dusk'], $presentationImages['living_room'], $presentationImages['bedroom']],
        'amenities' => ['Private Garden', 'Dedicated Parking', 'Water Storage', 'En-suite Bedrooms', 'Private Balcony'],
    ],
    [
        'landlord_email' => 'samuel.bekele@rentsmart.com',
        'title' => 'Sunridge Townhouse in Boku',
        'description' => 'Contemporary townhouse with practical family spacing, a comfortable main living area, and a neighborhood setting that feels settled but connected. It offers a balanced option for renters who want more room than an apartment without moving too far from the city center.',
        'price' => 52000,
        'listing_type' => 'rent',
        'property_type' => 'house',
        'status' => 'available',
        'bedrooms' => 3,
        'bathrooms' => 3,
        'area_sqm' => 210,
        'address' => 'Boku district, near the university corridor',
        'city' => 'Adama',
        'sub_city' => 'Boku',
        'is_featured' => 0,
        'images' => [$presentationImages['addis_street'], $presentationImages['kitchen'], $presentationImages['aerial']],
        'amenities' => ['Dedicated Parking', 'Backup Generator', 'Private Garden', 'Laundry Room'],
    ],
    [
        'landlord_email' => 'hana.alemu@rentsmart.com',
        'title' => 'Kezira Loft Residence',
        'description' => 'Modern one-bedroom loft with clean finishes, a smart open-plan layout, and a strong location for renters who want a compact but stylish home. It is especially suited to professionals who value light, efficiency, and a central Dire Dawa address.',
        'price' => 24000,
        'listing_type' => 'rent',
        'property_type' => 'studio',
        'status' => 'available',
        'bedrooms' => 1,
        'bathrooms' => 1,
        'area_sqm' => 74,
        'address' => 'Kezira avenue, near the commercial district',
        'city' => 'Dire Dawa',
        'sub_city' => 'Kezira',
        'is_featured' => 0,
        'images' => [$presentationImages['living_room'], $presentationImages['bedroom'], $presentationImages['aerial']],
        'amenities' => ['High-Speed Wi-Fi', 'Furnished', 'Laundry Room', 'Private Balcony'],
    ],
    [
        'landlord_email' => 'hana.alemu@rentsmart.com',
        'title' => 'Hilltop Apartment in Arada',
        'description' => 'Comfortable apartment with a straightforward layout, good natural ventilation, and easy day-to-day access to shops and neighborhood services. It is a practical rental for small families or professionals looking for stable value in Gondar.',
        'price' => 31000,
        'listing_type' => 'rent',
        'property_type' => 'apartment',
        'status' => 'available',
        'bedrooms' => 2,
        'bathrooms' => 1,
        'area_sqm' => 96,
        'address' => 'Arada area, close to the university route',
        'city' => 'Gondar',
        'sub_city' => 'Arada',
        'is_featured' => 0,
        'images' => [$presentationImages['exterior_balconies'], $presentationImages['kitchen'], $presentationImages['bedroom']],
        'amenities' => ['24/7 Security', 'Dedicated Parking', 'Water Storage'],
    ],
    [
        'landlord_email' => 'samuel.bekele@rentsmart.com',
        'title' => 'Kazanchis Penthouse Collection',
        'description' => 'Large penthouse currently preparing for its next tenant, with broad entertaining space, premium finishes, and strong skyline views. The listing is marked pending while final interior touch-ups and move-in scheduling are being completed.',
        'price' => 165000,
        'listing_type' => 'rent',
        'property_type' => 'apartment',
        'status' => 'pending',
        'bedrooms' => 4,
        'bathrooms' => 4,
        'area_sqm' => 300,
        'address' => 'Kazanchis business district, near UNECA',
        'city' => 'Addis Ababa',
        'sub_city' => 'Kazanchis',
        'is_featured' => 0,
        'images' => [$presentationImages['addis_dusk'], $presentationImages['open_plan'], $presentationImages['aerial']],
        'amenities' => ['24/7 Security', 'Elevator Access', 'Backup Generator', 'Gym Access', 'En-suite Bedrooms'],
    ],
];

$demoFavorites = [
    ['ruth.mekonnen@rentsmart.com', 'Bole Vista Residence'],
    ['ruth.mekonnen@rentsmart.com', 'Lakeview Apartment near Haile Resort'],
    ['ruth.mekonnen@rentsmart.com', 'Riverside Villa in Bahir Dar'],
    ['daniel.tadesse@rentsmart.com', 'Ayat Courtyard Villa'],
    ['daniel.tadesse@rentsmart.com', 'Hilltop Apartment in Arada'],
];

$demoMessages = [
    [
        'sender_email' => 'ruth.mekonnen@rentsmart.com',
        'recipient_email' => 'samuel.bekele@rentsmart.com',
        'property_title' => 'Bole Vista Residence',
        'body' => "Phone: +251912334455\n\nHello, I would like to schedule a viewing for this apartment later this week. Please let me know the best time to visit and whether service charges are included in the monthly rent.",
        'is_read' => 0,
    ],
    [
        'sender_email' => 'daniel.tadesse@rentsmart.com',
        'recipient_email' => 'samuel.bekele@rentsmart.com',
        'property_title' => 'Ayat Courtyard Villa',
        'body' => "Phone: +251913667788\n\nGood afternoon. Is the villa available for a long-term lease starting next month? I am also interested in whether the garden maintenance is handled separately.",
        'is_read' => 1,
    ],
    [
        'sender_email' => 'ruth.mekonnen@rentsmart.com',
        'recipient_email' => 'hana.alemu@rentsmart.com',
        'property_title' => 'Lakeview Apartment near Haile Resort',
        'body' => "Phone: +251912334455\n\nHi, I am interested in this listing for a one-year stay. Could you confirm if the apartment comes furnished and whether parking is available for one vehicle?",
        'is_read' => 0,
    ],
];

$demoContacts = [
    ['name' => 'Meron Desta', 'email' => 'meron.desta@example.com', 'phone' => '+251911778899', 'message' => 'Hello RentSmart team, I would like help preparing my first listing for publication next week.'],
    ['name' => 'Abel Terefe', 'email' => 'abel.terefe@example.com', 'phone' => '+251910223344', 'message' => 'I am looking for a two-bedroom rental in Addis Ababa and would like support narrowing down suitable options.'],
];

if ($refreshDemo) {
    echo "Refreshing presentation demo data...\n";
    resetPresentationData($db);
    seedPresentationDemo($db, $amenities, $demoUsers, $demoProperties, $demoFavorites, $demoMessages, $demoContacts);
    echo "Presentation demo reset complete.\n";
} else {
    seedAmenitiesIfMissing($db, $amenities);

    $propertyCount = (int)$db->query('SELECT COUNT(*) FROM properties')->fetchColumn();
    $userCount = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();

    if ($userCount === 0 && $propertyCount === 0) {
        echo "Seeding clean presentation demo data...\n";
        seedPresentationDemo($db, $amenities, $demoUsers, $demoProperties, $demoFavorites, $demoMessages, $demoContacts);
        echo "Presentation demo data created.\n";
    } else {
        echo "Existing application data detected.\n";
        echo "Run `php database/setup.php --fresh-demo` to rebuild the presentation dataset.\n";
    }
}

echo "\nDemo Credentials:\n";
echo "  Admin:     admin@rentsmart.com / admin123\n";
echo "  Landlord:  samuel.bekele@rentsmart.com / password\n";
echo "  Landlord:  hana.alemu@rentsmart.com / password\n";
echo "  Renter:    ruth.mekonnen@rentsmart.com / password\n";
echo "  Renter:    daniel.tadesse@rentsmart.com / password\n";
echo "\nSetup complete!\n";

function resetPresentationData(PDO $db): void
{
    $db->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach ([
        'contacts',
        'favorites',
        'messages',
        'property_amenities',
        'property_images',
        'properties',
        'amenities',
        'users',
    ] as $table) {
        $db->exec("TRUNCATE TABLE {$table}");
    }

    $db->exec('SET FOREIGN_KEY_CHECKS=1');
}

function seedAmenitiesIfMissing(PDO $db, array $amenities): void
{
    $existingAmenityCount = (int)$db->query('SELECT COUNT(*) FROM amenities')->fetchColumn();
    if ($existingAmenityCount > 0) {
        return;
    }

    $stmt = $db->prepare('INSERT INTO amenities (name, icon) VALUES (?, ?)');
    foreach ($amenities as [$name, $icon]) {
        $stmt->execute([$name, $icon]);
    }
}

function seedPresentationDemo(
    PDO $db,
    array $amenities,
    array $demoUsers,
    array $demoProperties,
    array $demoFavorites,
    array $demoMessages,
    array $demoContacts
): void {
    seedAmenitiesIfMissing($db, $amenities);

    $userStmt = $db->prepare(
        'INSERT INTO users (full_name, email, password, role, phone, is_active)
         VALUES (?, ?, ?, ?, ?, 1)'
    );

    foreach ($demoUsers as $user) {
        $userStmt->execute([
            $user['full_name'],
            strtolower($user['email']),
            password_hash($user['password'], PASSWORD_DEFAULT),
            $user['role'],
            $user['phone'],
        ]);
    }

    $userIdByEmail = [];
    foreach ($db->query('SELECT id, email FROM users')->fetchAll() as $row) {
        $userIdByEmail[strtolower($row['email'])] = (int)$row['id'];
    }

    $amenityIdByName = [];
    foreach ($db->query('SELECT id, name FROM amenities')->fetchAll() as $row) {
        $amenityIdByName[$row['name']] = (int)$row['id'];
    }

    $propertyStmt = $db->prepare(
        'INSERT INTO properties (
            landlord_id, title, description, price, listing_type, property_type, status,
            bedrooms, bathrooms, area_sqm, address, city, sub_city, is_featured, views
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $imageStmt = $db->prepare(
        'INSERT INTO property_images (property_id, image_path, is_primary, sort_order)
         VALUES (?, ?, ?, ?)'
    );

    $propertyAmenityStmt = $db->prepare(
        'INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)'
    );

    $propertyIdByTitle = [];

    foreach ($demoProperties as $index => $property) {
        $landlordId = $userIdByEmail[strtolower($property['landlord_email'])] ?? 0;

        $propertyStmt->execute([
            $landlordId,
            $property['title'],
            $property['description'],
            $property['price'],
            $property['listing_type'],
            $property['property_type'],
            $property['status'],
            $property['bedrooms'],
            $property['bathrooms'],
            $property['area_sqm'],
            $property['address'],
            $property['city'],
            $property['sub_city'],
            $property['is_featured'],
            120 + ($index * 37),
        ]);

        $propertyId = (int)$db->lastInsertId();
        $propertyIdByTitle[$property['title']] = $propertyId;

        foreach ($property['images'] as $sortOrder => $imagePath) {
            $imageStmt->execute([
                $propertyId,
                $imagePath,
                $sortOrder === 0 ? 1 : 0,
                $sortOrder,
            ]);
        }

        foreach ($property['amenities'] as $amenityName) {
            if (!isset($amenityIdByName[$amenityName])) {
                continue;
            }

            $propertyAmenityStmt->execute([
                $propertyId,
                $amenityIdByName[$amenityName],
            ]);
        }
    }

    $favoriteStmt = $db->prepare(
        'INSERT INTO favorites (user_id, property_id) VALUES (?, ?)'
    );

    foreach ($demoFavorites as [$email, $title]) {
        $userId = $userIdByEmail[strtolower($email)] ?? 0;
        $propertyId = $propertyIdByTitle[$title] ?? 0;

        if ($userId > 0 && $propertyId > 0) {
            $favoriteStmt->execute([$userId, $propertyId]);
        }
    }

    $messageStmt = $db->prepare(
        'INSERT INTO messages (sender_id, recipient_id, subject, body, is_read) VALUES (?, ?, ?, ?, ?)'
    );

    foreach ($demoMessages as $message) {
        $senderId = $userIdByEmail[strtolower($message['sender_email'])] ?? 0;
        $recipientId = $userIdByEmail[strtolower($message['recipient_email'])] ?? 0;
        $propertyId = $propertyIdByTitle[$message['property_title']] ?? 0;

        if ($senderId === 0 || $recipientId === 0 || $propertyId === 0) {
            continue;
        }

        $messageStmt->execute([
            $senderId,
            $recipientId,
            "[#{$propertyId}] {$message['property_title']}",
            $message['body'],
            $message['is_read'],
        ]);
    }

    $contactStmt = $db->prepare(
        'INSERT INTO contacts (name, email, phone, message, is_read) VALUES (?, ?, ?, ?, 0)'
    );

    foreach ($demoContacts as $contact) {
        $contactStmt->execute([
            $contact['name'],
            $contact['email'],
            $contact['phone'],
            $contact['message'],
        ]);
    }
}
