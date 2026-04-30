<?php
$db_path = __DIR__ . '/pupuseria.db';

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec("CREATE TABLE IF NOT EXISTS productos (
        id TEXT PRIMARY KEY,
        categoria TEXT NOT NULL,
        subcategoria TEXT DEFAULT NULL,
        nombre TEXT NOT NULL,
        emoji TEXT,
        precio REAL NOT NULL,
        imagen_url TEXT
    )");
    
    $db->exec("DELETE FROM productos");
    
    $menu = [
        "tradicionales" => [
            ["id"=>"t1", "name"=>"Revueltas", "emoji"=>"🫓", "price"=>0.95],
            ["id"=>"t2", "name"=>"Frijol/Queso", "emoji"=>"🫘,🧀", "price"=>0.95],
            ["id"=>"t3", "name"=>"Frijol", "emoji"=>"🫘", "price"=>0.95],
            ["id"=>"t4", "name"=>"Jamón/Queso", "emoji"=>"🍗,🧀", "price"=>0.95],
            ["id"=>"t5", "name"=>"Salami/Queso", "emoji"=>"🍖,🧀", "price"=>0.95],
            ["id"=>"t6", "name"=>"Chicharrón/Frijol", "emoji"=>"🐷,🫘", "price"=>0.95],
            ["id"=>"t7", "name"=>"Queso", "emoji"=>"🧀", "price"=>0.95],
            ["id"=>"t8", "name"=>"Queso/Loroco", "emoji"=>"🌿,🧀", "price"=>0.95],
            ["id"=>"t9", "name"=>"Ayote/Queso", "emoji"=>"🟢,🧀", "price"=>0.95],
            ["id"=>"t10", "name"=>"Mora/Queso", "emoji"=>"🍃,🧀", "price"=>0.95],
            ["id"=>"t11", "name"=>"Jalapeño/Queso", "emoji"=>"🌶️,🧀", "price"=>0.95],
            ["id"=>"t12", "name"=>"Zanahoria/Queso", "emoji"=>"🥕,🧀", "price"=>0.95],
            ["id"=>"t13", "name"=>"Ajo/Queso", "emoji"=>"🧄,🧀", "price"=>0.95],
            ["id"=>"t14", "name"=>"Chicharrón/Queso", "emoji"=>"🐷,🧀", "price"=>0.95],
            ["id"=>"t15", "name"=>"Cebolla/Queso", "emoji"=>"🧅,🧀", "price"=>0.95],
            ["id"=>"t16", "name"=>"Papelillo/Queso", "emoji"=>"🌱,🧀", "price"=>0.95]
        ],
        "especiales" => [
            ["id"=>"e1", "name"=>"Pollo/Queso", "emoji"=>"🐔,🧀", "price"=>1.25],
            ["id"=>"e2", "name"=>"Tocino/Queso", "emoji"=>"🥓,🧀", "price"=>1.25],
            ["id"=>"e3", "name"=>"Hongos/Queso", "emoji"=>"🍄,🧀", "price"=>1.25],
            ["id"=>"e4", "name"=>"Chorizo/Queso", "emoji"=>"🥩,🧀", "price"=>1.25],
            ["id"=>"e5", "name"=>"Camarón/Queso", "emoji"=>"🦐,🧀", "price"=>1.25],
            ["id"=>"e6", "name"=>"Chicharrón", "emoji"=>"🐷", "price"=>1.25],
            ["id"=>"e7", "name"=>"Revuelta/Jalapeño", "emoji"=>"🫓,🌶️", "price"=>1.25],
            ["id"=>"e8", "name"=>"Revuelta/Queso", "emoji"=>"🫓,🧀", "price"=>1.25]
        ],
        "tamalesYMas" => [
            ["id"=>"m1", "name"=>"Tamal de Pollo", "emoji"=>"🫔,🐔", "price"=>1.00],
            ["id"=>"m2", "name"=>"Tamal de Costilla", "emoji"=>"🫔,🍖", "price"=>1.00],
            ["id"=>"m3", "name"=>"Tamal de Azúcar", "emoji"=>"🫔", "price"=>1.00],
            ["id"=>"m4", "name"=>"Tamal Pisque", "emoji"=>"🫔,🫘", "price"=>0.60],
            ["id"=>"m5", "name"=>"Tamal chipilin", "emoji"=>"🫔,🌱", "price"=>0.60],
            ["id"=>"m6", "name"=>"Tamal de Elote", "emoji"=>"🫔,🌽", "price"=>0.50],
            ["id"=>"m7", "name"=>"Canoas ", "emoji"=>"🍌", "price"=>1.25, "img"=>"https://live.staticflickr.com/7336/10968270496_4be4a259a1_z.jpg"]
        ],
        "bebidasFrias" => [
            ["id"=>"bf46", "name"=>"Jugo del Valle pequeño", "emoji"=>"🧃", "price"=>0.45, "sub"=>"jugos y frescos", "img"=>"https://walmartsv.vtexassets.com/arquivos/ids/377942-800-450?v=638406922263900000&width=800&height=450&aspect=true"],
            ["id"=>"bf47", "name"=>"Coca Lata", "emoji"=>"🥫", "price"=>1.00, "sub"=>"gaseosas", "img"=>"https://media.istockphoto.com/id/458464735/es/foto/coca-cola.jpg?s=612x612&w=0&k=20&c=SnB7NqAiTxs3PQzWpSpwOiOncP1hbYHEP9zaDurvLwU="],
            ["id"=>"bf48", "name"=>"Coca Zero", "emoji"=>"🥤", "price"=>1.00, "sub"=>"gaseosas", "img"=>"https://walmartsv.vtexassets.com/arquivos/ids/372501/Gaseosa-Coca-Cola-Sin-Az-car-Lata-354-ml-2-3746.jpg?v=638392773749800000"],
            ["id"=>"bf12", "name"=>"Pepsi medium", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"img/pepsi-medium.jpg"],
            ["id"=>"bf49", "name"=>"Pepsi Black", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"img/pepsi-black.jpg"],
            ["id"=>"bf1", "name"=>"Fresco Natural", "emoji"=>"🍹", "price"=>0.50, "sub"=>"jugos y frescos"],
            ["id"=>"bf2", "name"=>"Fresco Natural Doble", "emoji"=>"🍹", "price"=>1.00, "sub"=>"jugos y frescos"],
            ["id"=>"bf7", "name"=>"Jugo de lata Petit", "emoji"=>"🥫", "price"=>0.75, "sub"=>"jugos y frescos", "img"=>"https://th.bing.com/th/id/OIP.1mwlG4SAuSeMtuGfzKtTkwHaJ4?w=150&h=199&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf8", "name"=>"Frutado pequeño", "emoji"=>"🧃", "price"=>0.45, "sub"=>"jugos y frescos"],
            ["id"=>"bf9", "name"=>"Jugo del Valle normal", "emoji"=>"🧃", "price"=>0.75, "sub"=>"jugos y frescos", "img"=>"https://walmartsv.vtexassets.com/arquivos/ids/486812-500-auto?v=638572752134100000&width=500&height=auto&aspect=true"],
            ["id"=>"bf3", "name"=>"Gaseosa normal", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"img/gaseosa-normal.jpg"],
            ["id"=>"bf4", "name"=>"Gaseosa lata", "emoji"=>"🥫", "price"=>1.00, "sub"=>"gaseosas", "img"=>"img/gaseosa-lata.jpg"],
            ["id"=>"bf10", "name"=>"Coca-Cola chiquita", "emoji"=>"🥤", "price"=>0.60, "sub"=>"gaseosas", "img"=>"img/coca-cola-chiquita.jpg"],
            ["id"=>"bf11", "name"=>"Té Lipton normal", "emoji"=>"🧊", "price"=>1.00, "sub"=>"jugos y frescos", "img"=>"https://th.bing.com/th/id/OIP.2MUMB9uXqNCHWCJtuQMIJQHaHa"],
            ["id"=>"bf13", "name"=>"Mirinda medium", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"img/mirinda-medium.jpg"],
            ["id"=>"bf14", "name"=>"7up medium", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"img/7up-medium.jpg"],
            ["id"=>"bf15", "name"=>"Té Lipton 1.5 lt", "emoji"=>"🧊", "price"=>1.50, "sub"=>"jugos y frescos", "img"=>"https://tse3.mm.bing.net/th/id/OIP.4XE9egjRlDWCSwMA50TDnwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3"],
            ["id"=>"bf16", "name"=>"Té Lipton 2.5 lt", "emoji"=>"🧊", "price"=>2.50, "sub"=>"jugos y frescos", "img"=>"https://tse3.mm.bing.net/th/id/OIP.RZA4kJlCOc9au434egG2qgHaHa"],
            ["id"=>"bf17", "name"=>"Pepsi 1.5 lt", "emoji"=>"🥤", "price"=>1.25, "sub"=>"gaseosas", "img"=>"https://th.bing.com/th/id/OIP.EjV5i1mmBGn_aFiUQv7cXgHaHa"],
            ["id"=>"bf18", "name"=>"Pepsi 3 lt", "emoji"=>"🥤", "price"=>2.50, "sub"=>"gaseosas", "img"=>"https://th.bing.com/th/id/OIP.Crl7RIdqrB3hLvb9PxiAVQHaHa"],
            ["id"=>"bf19", "name"=>"Coca-Cola 1.5 lt", "emoji"=>"🥤", "price"=>1.50, "sub"=>"gaseosas", "img"=>"https://www.bing.com/th/id/OIP.EJnLfC1GqNP6TuE9UZ62hQHaHa?w=202&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2"],
            ["id"=>"bf20", "name"=>"Coca-Cola 2.5 lt", "emoji"=>"🥤", "price"=>2.50, "sub"=>"gaseosas", "img"=>"https://www.bing.com/th/id/OIP.zYLd_lkAkEMTujL8bE97TgHaHa"],
            ["id"=>"bf5", "name"=>"Agua Cristal", "emoji"=>"💧", "price"=>0.60, "sub"=>"agua", "img"=>"https://th.bing.com/th/id/OIP.aL5LwSbiCsHNHehDKEQ2ngHaHa?w=181&h=181&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf23", "name"=>"Agua mineral", "emoji"=>"🥫", "price"=>1.00, "sub"=>"agua"],
            ["id"=>"bf24", "name"=>"Agua 1 lt", "emoji"=>"🥤", "price"=>1.00, "sub"=>"agua"],
            ["id"=>"bf6", "name"=>"Pilsener", "emoji"=>"🍺", "price"=>1.35, "sub"=>"cervezas", "img"=>"https://tse2.mm.bing.net/th/id/OIP.4eEPeeDxFMU_0kKWZhN-kgHaHa?pid=ImgDet&w=178&h=178&c=7&dpr=1,5&o=7&rm=3"],
            ["id"=>"bf21", "name"=>"Pilsener Lata", "emoji"=>"🥫", "price"=>1.35, "sub"=>"cervezas", "img"=>"https://th.bing.com/th/id/OIP.qTi0mrMt4IoCyQRLqZCldQHaHY?w=203&h=202&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf22", "name"=>"Salutary Lata", "emoji"=>"🥫", "price"=>0.75, "sub"=>"gaseosas", "img"=>"https://images.deliveryhero.io/image/pedidosya/products/642c746470d9b0f412578ed5.jpg"],
            ["id"=>"bf25", "name"=>"Pepsi o Mirinda plastica", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"https://www.bing.com/th/id/OIP.TRayWmSw-hq1pW2ygTT0gAHaK5?w=160&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2"],
            ["id"=>"bf26", "name"=>"Salutary plastica", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"https://lacolonia.vtexassets.com/arquivos/ids/248847-800-auto?v=638671089334830000&width=800&height=auto&aspect=true"],
            ["id"=>"bf50_salutary", "name"=>"Salutary pequeña", "emoji"=>"🥤", "price"=>0.65, "sub"=>"gaseosas"],
            ["id"=>"bf51_salutary", "name"=>"Salutary 1.5 litros", "emoji"=>"🥤", "price"=>1.50, "sub"=>"gaseosas"],
            ["id"=>"bf27", "name"=>"Pepsi plastica pequeña", "emoji"=>"🥤", "price"=>0.65, "sub"=>"gaseosas", "img"=>"https://res.cloudinary.com/riqra/image/upload/v1678811229/sellers/13/ebdvbbx1vfinzeslgk26.jpg"],
            ["id"=>"bf28", "name"=>"Pepsi Lata", "emoji"=>"🥤", "price"=>0.75, "sub"=>"gaseosas", "img"=>"https://www.bing.com/th/id/OIP.2gg274m6dp3ffTXbFILF7AHaHa?w=212&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2"],
            ["id"=>"bf29", "name"=>"Jugo de la Granja", "emoji"=>"🧃", "price"=>1.25, "sub"=>"jugos y frescos", "img"=>"https://latinfoodsatyourdoor.com/cdn/shop/files/jugo-de-naranja-de-la-granja.png?v=1747920030&width=1500"],
            ["id"=>"bf30", "name"=>"Jugo Tampico", "emoji"=>"🧃", "price"=>0.75, "sub"=>"jugos y frescos", "img"=>"https://tse2.mm.bing.net/th/id/OIP.p0_AL5NQOMiTlXTSbllB1AHaKg?pid=ImgDet&w=178&h=252&c=7&dpr=1,5&o=7&rm=3"],
            ["id"=>"bf31", "name"=>"Chocolatina ¼", "emoji"=>"🪶", "price"=>0.75, "sub"=>"jugos y frescos", "img"=>"https://th.bing.com/th/id/OIP.rN3M9hA2NnZn_j_WGI5qUgHaHa?w=191&h=191&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf32", "name"=>"Chocolatina ½", "emoji"=>"🪶", "price"=>1.00, "sub"=>"jugos y frescos", "img"=>"https://tse1.mm.bing.net/th/id/OIP.xQLiuaDYjqMbl6U4YHtt8gHaHa?rs=1&pid=ImgDetMain&o=7&rm=3"],
            ["id"=>"bf33", "name"=>"Powerade azul o rojo normal", "emoji"=>"⚡", "price"=>0.75, "sub"=>"energéticas", "img"=>"https://tse1.mm.bing.net/th/id/OIP.kOFYicqd8pcVAvTSBoSR0wHaGf?pid=ImgDet&w=178&h=156&c=7&dpr=1,5&o=7&rm=3"],
            ["id"=>"bf34", "name"=>"Powerade azul o rojo grande", "emoji"=>"⚡", "price"=>1.00, "sub"=>"energéticas", "img"=>"https://www.aperitissimo.fr/wp-content/uploads/2023/06/powerade-1024x576.png"],
            ["id"=>"bf35", "name"=>"Gatorade", "emoji"=>"⚡", "price"=>1.25, "sub"=>"energéticas", "img"=>"https://th.bing.com/th/id/OIP.Fn5kDtpgaGHqziRBinGS_gHaHa?w=212&h=212&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf36", "name"=>"Raptor", "emoji"=>"🔋", "price"=>1.00, "sub"=>"energéticas", "img"=>"https://th.bing.com/th/id/OIP.LWUVimpPTHEeW1G9mHBhAwHaHa?w=176&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf37", "name"=>"Monster", "emoji"=>"🔋", "price"=>2.50, "sub"=>"energéticas", "img"=>"https://th.bing.com/th/id/OIP.qBLWsqBIw9a2u78d_6546gHaHa?w=218&h=218&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf38", "name"=>"Be Light", "emoji"=>"💧", "price"=>0.75, "sub"=>"energéticas", "img"=>"https://th.bing.com/th/id/OIP.Vl-F8GMDqLmGhvwzMj99eAHaHa?w=172&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf39", "name"=>"Golden", "emoji"=>"🍺", "price"=>1.25, "sub"=>"cervezas", "img"=>"https://th.bing.com/th/id/OIP.sw8ZqN4yg4P1q6Wsfp6nkQHaHa?w=163&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf40", "name"=>"Corona", "emoji"=>"🍺", "price"=>1.75, "sub"=>"cervezas", "img"=>"https://th.bing.com/th/id/OIP.Ntmj6jczeQzPrE0mYwFDvwHaHa?w=202&h=202&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf41", "name"=>"Pilsener grande", "emoji"=>"🍺", "price"=>2.50, "sub"=>"cervezas", "img"=>"https://th.bing.com/th/id/OIP.hh0TXCpGb1KpD2_xKsn8jAAAAA?w=115&h=186&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf42", "name"=>"Regia grande", "emoji"=>"🍺", "price"=>2.25, "sub"=>"cervezas", "img"=>"https://tse4.mm.bing.net/th/id/OIP.jRDtQkH9yzxO8r71mwDgWwHaSC?pid=ImgDet&w=144&h=350&c=7&dpr=1,5&o=7&rm=3"],
            ["id"=>"bf43", "name"=>"Suprema", "emoji"=>"🍺", "price"=>1.25, "sub"=>"cervezas", "img"=>"https://tse3.mm.bing.net/th/id/OIP.lnfg0oJ3v-WkqGFMFW51qQAAAA?rs=1&pid=ImgDetMain&o=7&rm=3"],
            ["id"=>"bf44", "name"=>"Coca Cola 3 lt", "emoji"=>"🥤", "price"=>3.00, "sub"=>"gaseosas", "img"=>"https://www.bing.com/th/id/OIP.PL876p8XUtZezqUWRXNSdwHaHa?w=199&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.5&pid=3.1&rm=2"],
            ["id"=>"bf45", "name"=>"Frutado Normal", "emoji"=>"🥤", "price"=>0.75, "sub"=>"jugos y frescos", "img"=>"https://th.bing.com/th/id/OIP.gDMALHNSBuy1epqx6OzLhQHaFj?w=220&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3"],
            ["id"=>"bf50", "name"=>"Grapette Lata", "emoji"=>"🥫", "price"=>0.75, "sub"=>"gaseosas"],
            ["id"=>"bf51_grapette", "name"=>"Grapette Botella", "emoji"=>"🥤", "price"=>0.60, "sub"=>"gaseosas"],
            ["id"=>"bf52", "name"=>"Jugo del Valle Litro", "emoji"=>"🧃", "price"=>1.50, "sub"=>"jugos y frescos"],
            ["id"=>"bf53", "name"=>"Jugo del Valle 2 Litros", "emoji"=>"🧃", "price"=>2.00, "sub"=>"jugos y frescos"],
            ["id"=>"bf54", "name"=>"Red Bull Lata", "emoji"=>"🔋", "price"=>1.25, "sub"=>"energéticas"],
            ["id"=>"bf55", "name"=>"Gaseosa de sabores 2.5 lt", "emoji"=>"🥤", "price"=>2.50, "sub"=>"gaseosas"],
            ["id"=>"bf56", "name"=>"Energizante AMP", "emoji"=>"🔋", "price"=>1.00, "sub"=>"energéticas"],
            ["id"=>"bf57", "name"=>"Jugo Aloe vera", "emoji"=>"🧃", "price"=>1.25, "sub"=>"jugos y frescos"],
            ["id"=>"bf58", "name"=>"Agua Acqua", "emoji"=>"💧", "price"=>0.60, "sub"=>"agua"],
            ["id"=>"bf59", "name"=>"Cubata cerveza", "emoji"=>"🍺", "price"=>1.25, "sub"=>"cervezas"]
        ],
        "bebidasCalientes" => [
            ["id"=>"bc1", "name"=>"Café", "emoji"=>"🫖", "price"=>0.50],
            ["id"=>"bc2", "name"=>"Café con leche", "emoji"=>"🫖,🥛", "price"=>0.75],
            ["id"=>"bc3", "name"=>"Chocolate", "emoji"=>"☕", "price"=>0.75],
            ["id"=>"bc4", "name"=>"Chocolate con leche", "emoji"=>"☕,🥛", "price"=>0.75],
            ["id"=>"bc5", "name"=>"Leche Caliente", "emoji"=>"🍵", "price"=>0.85],
            ["id"=>"bc6", "name"=>"Cappuccino", "emoji"=>"☕", "price"=>1.00],
            ["id"=>"bc7", "name"=>"Café con cremora", "emoji"=>"🫖", "price"=>0.75]
        ]
    ];

    $stmt = $db->prepare("INSERT INTO productos (id, categoria, subcategoria, nombre, emoji, precio, imagen_url) VALUES (:id, :cat, :sub, :nom, :emoji, :precio, :img)");

    foreach ($menu as $categoria => $productos) {
        foreach ($productos as $p) {
            $stmt->execute([
                ':id' => $p['id'],
                ':cat' => $categoria,
                ':sub' => isset($p['sub']) ? $p['sub'] : null,
                ':nom' => $p['name'],
                ':emoji' => isset($p['emoji']) ? $p['emoji'] : null,
                ':precio' => $p['price'],
                ':img' => isset($p['img']) ? $p['img'] : null
            ]);
        }
    }

    echo "Menu migrado con exito a pupuseria.db\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
