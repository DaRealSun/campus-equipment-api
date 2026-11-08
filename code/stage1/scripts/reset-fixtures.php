<?php
declare(strict_types=1);
if (getenv('ALLOW_FIXTURE_RESET') !== '1' || getenv('DB_DATABASE') !== 'csc640_equipment') {
    fwrite(STDERR, "Fixture reset requires the isolated course database and ALLOW_FIXTURE_RESET=1.\n");
    exit(1);
}
$db = new PDO('mysql:host='.getenv('DB_HOST').';dbname=csc640_equipment;charset=utf8mb4', getenv('DB_USERNAME'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->beginTransaction();
try {
    foreach (['tokens','loans','equipment','users'] as $table) { $db->exec('DELETE FROM '.$table); }
    $user = $db->prepare('INSERT INTO users(id,name,email,password_hash,role) VALUES (?,?,?,?,?)');
    foreach ([[101,'Staff','staff@example.test','staff'],[102,'Borrower One','borrower1@example.test','borrower'],[103,'Borrower Two','borrower2@example.test','borrower']] as [$id,$name,$email,$role]) {
        $user->execute([$id,$name,$email,password_hash('CourseFixturePassword-42',PASSWORD_DEFAULT),$role]);
    }
    $equipment = $db->prepare('INSERT INTO equipment(id,name,description) VALUES (?,?,?)');
    $equipment->execute([1001,'Tripod','Available fixture']);
    $equipment->execute([1002,'Camera','Loaned fixture']);
    $token = $db->prepare('INSERT INTO tokens(token_hash,user_id,expires_at) VALUES (?,?,DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 DAY))');
    foreach ([101=>'fixture-staff-token',102=>'fixture-borrower1-token',103=>'fixture-borrower2-token'] as $id=>$value) { $token->execute([hash('sha256',$value),$id]); }
    $db->exec('INSERT INTO loans(id,user_id,equipment_id,borrowed_at) VALUES (2001,103,1002,UTC_TIMESTAMP())');
    $db->commit();
    echo "Synthetic acceptance fixtures reset in csc640_equipment.\n";
} catch (Throwable $error) { $db->rollBack(); throw $error; }
