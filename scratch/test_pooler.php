<?php
$host = 'aws-1-ap-southeast-1.pooler.supabase.com';
$port = '5432';
$db = 'postgres';
$user = 'postgres.qbueelomakropipgxwoy';
$pass = 'Sismokap23#';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Koneksi pooler SUKSES!\n";
} catch (Exception $e) {
    echo "Koneksi pooler GAGAL: " . $e->getMessage() . "\n";
}
