<?php

include __DIR__ . '/../../../../../include/config.php'; // Database Configuration



// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function savePrescription($data)
{
    global $lms_link;
    // Sanitize data to prevent SQL injection
    foreach ($data as $key => $value) {
        if ($key != "drugsList") {
            $data[$key] = $lms_link->real_escape_string($value);
        }
    }

    $drugsList = json_decode($data["drugsList"], true); // Decoding JSON string to array
    $drugListArray = implode(', ', $drugsList);

    // Check if the record already exists based on 'id'
    if ($data['prescriptionID'] != '0') {
        // Update the record
        $updateQuery = "UPDATE `prescription` SET
                        `prescription_name` = '{$data['patientName']}',
                        `prescription_status` = '{$data['prescriptionStatus']}',
                        `Pres_Name` = '{$data['patientName']}',
                        `pres_date` = '{$data['patientDate']}',
                        `Pres_Age` = '{$data['patientAge']}',
                        `Pres_Method` = '{$data['usingMethod']}',
                        `doctor_name` = '{$data['doctorName']}',
                        `notes` = '{$data['drugDescription']}', 
                        `drugs_list` = '{$drugListArray}'
                        WHERE `prescription_id` = '{$data['prescriptionID']}'";

        $result = $lms_link->query($updateQuery);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Prescription updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . $lms_link->error]);
        }
    } else {
        $prescription_id = getNextPrescriptionId();
        // Insert a new record
        $insertQuery = "INSERT INTO `prescription` (`prescription_name`, `prescription_status`, `Pres_Name`, `pres_date`, `Pres_Age`, `Pres_Method`, `doctor_name`, `notes`, `drugs_list`, `prescription_id`)
                        VALUES (
                            '{$data['patientName']}',
                            '{$data['prescriptionStatus']}',
                            '{$data['patientName']}',
                            '{$data['patientDate']}',
                            '{$data['patientAge']}',
                            '{$data['usingMethod']}',
                            '{$data['doctorName']}',
                            '{$data['drugDescription']}',
                            '{$drugListArray}', 
                            '{$prescription_id}'
                        )";

        $result = $lms_link->query($insertQuery);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Prescription Saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting record: ' . $lms_link->error]);
        }
    }

    $lms_link->close();
}


function getNextPrescriptionId()
{

    global $lms_link;
    // Fetch the next prescription ID
    $nextIdQuery = "SELECT MAX(CAST(SUBSTRING(`prescription_id`, 4) AS UNSIGNED)) + 1 AS next_id FROM `prescription`";
    $nextIdResult = $lms_link->query($nextIdQuery);

    if ($nextIdResult) {
        $row = $nextIdResult->fetch_assoc();
        $nextId = $row['next_id'];

        // Construct the next prescription ID
        $newPrescriptionId = 'PRE' . $nextId;

        return $newPrescriptionId;
    } else {
        // Handle the error if the query fails
        return null;
    }
}

function GetPrescriptions()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `prescription`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['prescription_id']] = $row;
        }
    }

    return $ArrayResult;
}


function GetPrescriptionAllCoversDpad()
{
    global $lms_link;
    $ArrayResult = array();
    // Fetch the next prescription ID
    $nextIdQuery = "SELECT `prescription_id`, `drugs_list` FROM `prescription`";
    $nextIdResult = $lms_link->query($nextIdQuery);


    if ($nextIdResult->num_rows > 0) {
        while ($row = $nextIdResult->fetch_assoc()) {
            $drugs_list = $row['drugs_list'];
            $drugListArray = explode(', ', $drugs_list);

            $ArrayResult[$row['prescription_id']] = $drugListArray;
        }
    }
    return $ArrayResult;
}


function GetPrescriptionCoversDpad($prescriptionId)
{
    global $lms_link;
    // Fetch the next prescription ID
    $nextIdQuery = "SELECT `drugs_list` FROM `prescription` WHERE `prescription_id` LIKE '$prescriptionId'";
    $nextIdResult = $lms_link->query($nextIdQuery);

    if ($nextIdResult) {
        $row = $nextIdResult->fetch_assoc();
        $drugs_list = $row['drugs_list'];
        $drugListArray = explode(', ', $drugs_list);

        return $drugListArray;
    } else {
        // Handle the error if the query fails
        return null;
    }
}



function DpadSubmittedAnswersByCover($loggedUser, $coverID, $prescriptionID, $answer_status)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `answer_id`, `pres_id`, `cover_id`, `date`, `name`, `drug_name`, `drug_type`, `drug_qty`, `morning_qty`, `afternoon_qty`, `evening_qty`, `night_qty`, `meal_type`, `using_type`, `at_a_time`, `hour_qty`, `additional_description`, `created_at`, `created_by`, `answer_status`, `score` 
            FROM `prescription_answer_submission`  
            WHERE `created_by` = ? AND `pres_id` = ? AND `cover_id` = ? AND `answer_status` = ?";

    $stmt = $lms_link->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $loggedUser, $prescriptionID, $coverID, $answer_status);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }

        $stmt->close();
    }

    return $ArrayResult;
}




function DpadGetSavedAnswersByCover($prescriptionID, $coverID)
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `answer_id`, `pres_id`, `cover_id`, `date`, `name`, `drug_name`, `drug_type`, `drug_qty`, `morning_qty`, `afternoon_qty`, `evening_qty`, `night_qty`, `meal_type`, `using_type`, `at_a_time`, `hour_qty`, `additional_description`, `created_at`, `created_by` FROM `prescription_answer` WHERE `pres_id` LIKE '$prescriptionID' AND `cover_id` LIKE '$coverID'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


// -----    

function generateSriLankanPhoneNumber()
{
    $areaCode = '0' . rand(71, 77); // Sri Lankan mobile area codes
    $phoneNumber = $areaCode . '-' . rand(1000000, 9999999);

    return $phoneNumber;
}

function generateDoctorDetails()
{
    $doctorFirstNames = [
        'Dr. Sunil', 'Dr. Malith', 'Dr. Ruwan', 'Dr. Dilshan', 'Dr. Prasanna', 'Dr. Ayuru', 'Dr. Manoj', 'Dr. Isham', 'Dr. Tharindu', 'Dr. Nadeesha',
        'Dr. Chathuranga', 'Dr. Kasun', 'Dr. Udara', 'Dr. Asanka', 'Dr. Saman', 'Dr. Shalika', 'Dr. Nimal', 'Dr. Lakmal', 'Dr. Chandana', 'Dr. Jayantha',
        'Dr. Rajitha', 'Dr. Supun', 'Dr. Thilina', 'Dr. Anuradha', 'Dr. Chaminda', 'Dr. Roshan', 'Dr. Lasantha', 'Dr. Indika', 'Dr. Damith', 'Dr. Isuru',
        'Dr. Madhawa', 'Dr. Mahesh', 'Dr. Kamal', 'Dr. Hiran', 'Dr. Nuwan', 'Dr. Chamara', 'Dr. Channa', 'Dr. Sujeewa', 'Dr. Dinesh', 'Dr. Dhanushka',
        'Dr. Nuwantha', 'Dr. Dasun', 'Dr. Thushara', 'Dr. Dulan', 'Dr. Harsha', 'Dr. Nipuna', 'Dr. Nishan', 'Dr. Kusal', 'Dr. Anjana'
    ];

    $specializations = [
        'Cardiologist', 'Orthopedic Surgeon', 'Neurologist', 'Dermatologist', 'Ophthalmologist', 'Pediatrician', 'Gynecologist', 'ENT Specialist',
        'Gastroenterologist', 'Urologist', 'Psychiatrist', 'Endocrinologist', 'Pulmonologist', 'Dentist', 'Oncologist', 'Rheumatologist', 'Nephrologist',
        'General Surgeon', 'Plastic Surgeon', 'Radiologist', 'Emergency Medicine Specialist', 'Allergist', 'Anesthesiologist', 'Internal Medicine Specialist'
    ];

    $lastNames = [
        'Rathnayaka', 'Pernando', 'Malik', 'Jayawardena', 'Dissanayake', 'Amarle', 'Manthri', 'Ishanka', 'Fernando', 'Perera',
        'Silva', 'Bandara', 'Gunawardena', 'Wickramasinghe', 'Weerasinghe', 'Seneviratne', 'Karunaratne', 'Fonseka', 'Wijewardena',
        'Samarasinghe', 'Liyanage', 'Kumara', 'Herath', 'Rajapaksa', 'De Silva', 'Jayasinghe', 'Kulathunga', 'Wijesekara', 'Siriwardena',
        'Bandaranaike', 'Ranasinghe', 'Abeysinghe', 'Kumarasinghe', 'Peris', 'Jayasuriya', 'Fernandopulle', 'Gunasekara', 'Karunatilaka', 'Wickramaarachchi',
        'De Alwis', 'Fernando', 'Aponso', 'Gunathilaka', 'Mendis', 'Herath', 'Munasinghe', 'Koralage', 'Lakmal', 'Rathnayake'
    ];

    $randomFirstName = $doctorFirstNames[array_rand($doctorFirstNames)];
    $randomLastName = $lastNames[array_rand($lastNames)];
    $randomSpecialization = $specializations[array_rand($specializations)];

    $doctorDetails = [
        'DoctorName' => $randomFirstName . ' ' . $randomLastName,
        'Specialization' => $randomSpecialization,
        'ContactNumber' => generateSriLankanPhoneNumber(),
        'Email' => strtolower($randomFirstName) . '.' . strtolower($randomLastName) . '@hospital.com',
    ];

    return $doctorDetails;
}

function generateRandomAddress()
{
    $streets = [
        'Main Street', 'Park Road', 'Highway Avenue', 'Sunset Boulevard', 'Ocean Drive', 'Mountain View Lane',
        'Greenfield Terrace', 'Maple Avenue', 'Sunnyvale Street', 'Riverfront Drive', 'Meadow Lane', 'Willow Street',
        'Golden Gate Boulevard', 'Pinecrest Lane', 'Riverside Drive', 'Harmony Lane', 'Cypress Street', 'Lakeside Avenue',
        'Emerald Street', 'Majestic View Drive', 'Sycamore Lane', 'Serene Gardens Boulevard', 'Rosewood Street', 'Sunrise Avenue',
        'Oakwood Lane', 'Tranquil Terrace', 'Victory Street', 'Hilltop Avenue', 'Whispering Pines Lane', 'Blossom Street',
        'Enchanted Gardens Boulevard', 'Fairway Lane', 'Cobblestone Street', 'Crystal Clear Avenue', 'Silver Maple Lane', 'Royal Gardens Drive',
        'Thunderbird Lane', 'Eagle Crest Boulevard', 'Gentle Breeze Street', 'Harbor View Drive', 'Prairie Lane', 'Dreamy Hollow Avenue',
        'Cascade Street', 'Mystic Meadow Lane', 'Silverado Avenue', 'Amber Street', 'Quail Ridge Lane', 'Eternal Bliss Boulevard'
    ];

    $cities = [
        'Colombo', 'Kandy', 'Galle', 'Jaffna', 'Negombo', 'Matara',
        'Anuradhapura', 'Trincomalee', 'Polonnaruwa', 'Badulla', 'Kurunegala', 'Ratnapura',
        'Hambantota', 'Batticaloa', 'Kalutara', 'Gampaha', 'Matale', 'Nuwara Eliya',
        'Ampara', 'Kegalle', 'Puttalam', 'Mannar', 'Vavuniya', 'Kilinochchi',
        'Monaragala', 'Mulaitivu', 'Beruwala', 'Chilaw', 'Panadura', 'Balangoda',
        'Dambulla', 'Hikkaduwa', 'Weligama', 'Dickwella', 'Eheliyagoda', 'Kegalle',
        'Nawalapitiya', 'Mahiyanganaya', 'Hatton', 'Baddegama', 'Wariyapola', 'Kattankudy',
        'Homagama', 'Wattala', 'Minuwangoda', 'Horana', 'Balapitiya', 'Tangalle'
    ];


    $randomStreet = $streets[array_rand($streets)];
    $randomCity = $cities[array_rand($cities)];

    $randomAddress = rand(100, 999) . ' ' . ucfirst($randomStreet) . ', ' . ucfirst($randomCity);

    return $randomAddress;
}

function generateHumanDetails()
{
    $maleFirstNames = [
        'Sunil', 'Malith', 'Ruwan', 'Dilshan', 'Prasanna', 'Ayuru', 'Manoj', 'Isham', 'Tharindu', 'Nadeesha',
        'Chathuranga', 'Kasun', 'Udara', 'Asanka', 'Saman', 'Shalika', 'Nimal', 'Lakmal', 'Chandana', 'Jayantha',
        'Rajitha', 'Supun', 'Thilina', 'Anuradha', 'Chaminda', 'Roshan', 'Lasantha', 'Indika', 'Damith', 'Isuru',
        'Madhawa', 'Mahesh', 'Kamal', 'Hiran', 'Nuwan', 'Chamara', 'Channa', 'Sujeewa', 'Dinesh', 'Dhanushka',
        'Nuwantha', 'Dasun', 'Thushara', 'Dulan', 'Harsha', 'Nipuna', 'Nishan', 'Kusal', 'Anjana'
    ];

    $femaleFirstNames = [
        'Sanduni', 'Mala', 'Ruwani', 'Dilini', 'Pradeepa', 'Ayushi', 'Maneesha', 'Ishara', 'Tharindika', 'Nisansala',
        'Chathurika', 'Kasuni', 'Udari', 'Asini', 'Samadhi', 'Shamika', 'Nimashi', 'Lakmini', 'Chamari', 'Jayani',
        'Harshani', 'Dilshani', 'Thilini', 'Anuradha', 'Chamila', 'Rashmi', 'Madhushi', 'Kavindi', 'Nethmi', 'Upeksha',
        'Piumi', 'Ishara', 'Tharushi', 'Ishini', 'Amali', 'Udeshika', 'Hashini', 'Shashini', 'Shenali', 'Hansi',
        'Dinushi', 'Shanika', 'Kumari', 'Nuwanthi', 'Anusha', 'Thilanka', 'Dinara', 'Dulani', 'Dilukshi', 'Hasitha'
    ];

    $lastNames = [
        'Rathnayaka', 'Pernando', 'Malik', 'Jayawardena', 'Dissanayake', 'Amarle', 'Manthri', 'Ishanka', 'Fernando', 'Perera',
        'Silva', 'Bandara', 'Gunawardena', 'Wickramasinghe', 'Weerasinghe', 'Seneviratne', 'Karunaratne', 'Fonseka', 'Wijewardena',
        'Samarasinghe', 'Liyanage', 'Kumara', 'Herath', 'Rajapaksa', 'De Silva', 'Jayasinghe', 'Kulathunga', 'Wijesekara', 'Siriwardena',
        'Bandaranaike', 'Ranasinghe', 'Abeysinghe', 'Kumarasinghe', 'Peris', 'Jayasuriya', 'Fernandopulle', 'Gunasekara', 'Karunatilaka', 'Wickramaarachchi',
        'De Alwis', 'Fernando', 'Aponso', 'Gunathilaka', 'Mendis', 'Herath', 'Munasinghe', 'Koralage', 'Lakmal', 'Rathnayake'
    ];


    // Randomly select gender
    $gender = rand(0, 1) ? 'Male' : 'Female';

    // Randomly select a first name based on gender
    $firstName = ($gender == 'Male') ? $maleFirstNames[array_rand($maleFirstNames)] : $femaleFirstNames[array_rand($femaleFirstNames)];

    // Randomly select a last name
    $lastName = $lastNames[array_rand($lastNames)];

    // Generate random age between 18 and 70
    $age = rand(18, 70);

    // Generate a random phone number
    $phoneNumber = generateSriLankanPhoneNumber();

    // Generate a random email address
    $email = strtolower($firstName) . '.' . strtolower($lastName) . rand(0, 10) . '@gmail.com';

    // Generate a fake address for demonstration purposes
    $address = generateRandomAddress();


    return [
        'Name' => $firstName . ' ' . $lastName,
        'Age' => $age,
        'Sex' => $gender,
        'Address' => $address,
        'PhoneNumber' => $phoneNumber,
        'EmailAddress' => $email,
    ];
}

function generateMedicinePrescription()
{
    $randomAge = rand(1, 80);
    $posProducts = GetPOSProducts();
    $medicineDetails = GetLinkedProducts();
    $medicines = GetLinkedProductsItem();

    $dosages = ['1 tablet', '2 tablets', '1 capsule', '1 teaspoon', '1 tablespoon', '1 injection', '1 puff', '1 suppository', '1 drop'];

    $methods = ['2D', '3D', '5D', '1W', '1M'];

    $harmfulCombinations = [
        ['Paracetamol', 'Ibuprofen'],
        ['Loratadine', 'Cetirizine'],
        ['Levothyroxine', 'Calcium Supplement'],
        // Add more harmful combinations as needed
    ];


    $randomPrescriptionName = generateHumanDetails()['Name'];
    $randomPrescriptionSex = generateHumanDetails()['Sex'];
    $numberOfMedicines = rand(1, 5); // You can adjust the number of medicines per prescription

    $prescribedMedicines = [];
    $selectedMedicines = []; // Keep track of selected medicines

    for ($i = 0; $i < $numberOfMedicines; $i++) {
        do {
            $randomMedicine = $medicines[array_rand($medicines)];

            $selectedMedicine = $medicineDetails[$randomMedicine];

            $methodListString = $selectedMedicine["method_list"];
            $avaliableMethods = explode(', ', $methodListString);
        } while (in_array($randomMedicine, $selectedMedicines));

        $randomDosage = $dosages[array_rand($dosages)];
        $randomMethod = $avaliableMethods[array_rand($avaliableMethods)];

        // Check for harmful combinations
        foreach ($harmfulCombinations as $harmfulCombination) {
            if (in_array($randomMedicine, $harmfulCombination) && in_array($prescribedMedicines, $harmfulCombination)) {
                // If the combination is harmful, skip this medicine
                continue 2; // Skip to the next iteration of the outer loop
            }
        }

        $prescribedMedicines[] = [
            'Medicine' => $randomMedicine,
            'Dosage' => $randomDosage,
            'randomMethod' => $randomMethod,
        ];

        $selectedMedicines[] = $randomMedicine;
    }

    $randomMethod = $methods[array_rand($methods)];
    $randomDoctor = generateDoctorDetails();
    $randomDate = generateRandomDate('2022-01-01', date('Y-m-d'));


    $medicinePrescription = [
        'PrescriptionName' => $randomPrescriptionName,
        'PrescriptionDate' => $randomDate,
        'PatientAge' => $randomAge, // Adjust as needed
        'randomPrescriptionSex' => $randomPrescriptionSex,
        'PrescriptionMethod' => $randomMethod,
        'Doctor' => $randomDoctor,
        'Medicines' => $prescribedMedicines
    ];

    return $medicinePrescription;
}


function generateRandomDate($startDate, $endDate)
{
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);

    $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

    return date('Y-m-d', $randomTimestamp);
}

function OverallGradeDpad($loggedUser)
{
    global $lms_link;
    $correctCount = $inCorrectCount = $correctScore = $inCorrectScore = $overallGrade = 0;

    $prescriptions = GetActivePrescriptions(); // Modify the function to directly retrieve active prescriptions.
    $scorePerPrescription = 10;
    $savedAnswers = GetSubmittedAnswersByUser($loggedUser);

    $totalEnvelopes = 0;
    foreach ($prescriptions as $selectedArray) {
        $prescriptionId = $selectedArray['prescription_id'];
        $medicineEnvelopes = GetPrescriptionCoversDpad($prescriptionId);

        if ($medicineEnvelopes) {
            $medicineCount = count($medicineEnvelopes);
            $totalEnvelopes += $medicineCount;

            $correctCount += GetSubmittedAnswersCount($loggedUser, $prescriptionId, 'Correct', $medicineCount, $savedAnswers);
            $inCorrectCount += GetSubmittedAnswersCount($loggedUser, $prescriptionId, 'In-Correct', $medicineCount, $savedAnswers);
        }
    }

    $correctScore = $correctCount * $scorePerPrescription;
    $inCorrectScore = $inCorrectCount * -1;

    $prescriptionCount = count($prescriptions);

    if ($prescriptionCount > 0) {
        $overallGrade = (($correctScore + $inCorrectScore) / ($totalEnvelopes * $scorePerPrescription)) * 100;
    }

    $resultArray = array('overallGrade' => $overallGrade, 'correctScore' => $correctScore, 'inCorrectScore' => $inCorrectScore, 'prescriptionCount' => $prescriptionCount, 'totalEnvelopes' => $totalEnvelopes);

    return $resultArray;
}



function GradeByPrescription($prescriptionId, $loggedUser, $savedAnswers)
{
    $scorePerPrescription = 10;
    $scorePerIncorrectPrescription = -1;

    $correctCount = $inCorrectCount = $correctScore = $inCorrectScore = $prescriptionGrade = 0;
    $totalEnvelopes = 0;
    $medicineEnvelopes = GetPrescriptionCoversDpad($prescriptionId);

    if ($medicineEnvelopes) {
        $medicineCount = count($medicineEnvelopes);
        $totalEnvelopes += $medicineCount;

        $correctCount += GetSubmittedAnswersCount($loggedUser, $prescriptionId, 'Correct', $medicineCount, $savedAnswers);
        $inCorrectCount += GetSubmittedAnswersCount($loggedUser, $prescriptionId, 'In-Correct', $medicineCount, $savedAnswers);
    }

    $correctScore = $correctCount * $scorePerPrescription;
    $inCorrectScore = $inCorrectCount * $scorePerIncorrectPrescription;

    $totalScore = $correctScore + $inCorrectScore;
    $prescriptionGrade = (($totalScore) / ($totalEnvelopes * $scorePerPrescription)) * 100;

    $resultArray = array('prescriptionGrade' => $prescriptionGrade, 'correctScore' => $correctScore, 'inCorrectScore' => $inCorrectScore, 'totalEnvelopes' => $totalEnvelopes, 'scorePerPrescription' => $scorePerPrescription, 'scorePerIncorrectPrescription' => $scorePerIncorrectPrescription, 'totalScore' => $totalScore, 'correctCount' => $correctCount, 'inCorrectCount' => $inCorrectCount);

    return $resultArray;
}

function GradeByEnvelope($prescriptionId, $loggedUser, $savedAnswers, $coverId)
{
    $scorePerPrescription = 10;
    $scorePerIncorrectPrescription = -1;
    $correctCount = $inCorrectCount = $correctScore = $inCorrectScore = $coverGrade = 0;

    $correctCount += GetSubmittedAnswersCountByCoverId($loggedUser, $prescriptionId, 'Correct', $coverId, $savedAnswers);
    $inCorrectCount += GetSubmittedAnswersCountByCoverId($loggedUser, $prescriptionId, 'In-Correct', $coverId, $savedAnswers);

    $correctScore = $correctCount * $scorePerPrescription;
    $inCorrectScore = $inCorrectCount * $scorePerIncorrectPrescription;

    $totalScore = $correctScore + $inCorrectScore;
    $coverGrade = ($totalScore /  $scorePerPrescription) * 100;

    $resultArray = array('coverGrade' => $coverGrade, 'correctScore' => $correctScore, 'inCorrectScore' => $inCorrectScore, 'totalScore' => $totalScore);

    return $resultArray;
}

function GetActivePrescriptions()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `prescription` WHERE `prescription_status` LIKE 'Active'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['prescription_id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetSubmittedAnswersByUser($loggedUser)
{
    global $lms_link;

    $ArrayResult = array();

    $sql = "SELECT `id`, `answer_id`, `pres_id`, `cover_id`, `date`, `name`, `drug_name`, `drug_type`, `drug_qty`, `morning_qty`, `afternoon_qty`, `evening_qty`, `night_qty`, `meal_type`, `using_type`, `at_a_time`, `hour_qty`, `additional_description`, `created_at`, `created_by`, `answer_status`, `score` 
    FROM `prescription_answer_submission`  
    WHERE `created_by` = '$loggedUser'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}


function GetSubmittedAnswersByAllUser($courseCode)
{
    global $lms_link;

    $ArrayResult = array();

    $sql = "SELECT pas.*, pas.answer_id, sc.course_code
    FROM prescription_answer_submission pas
    JOIN user_full_details ufd ON pas.created_by = ufd.username
    JOIN student_course sc ON ufd.student_id = sc.student_id
    WHERE sc.course_code LIKE '$courseCode'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }

    return $ArrayResult;
}


function GetSubmittedAnswersCount($loggedUser, $prescriptionId, $status, $medicineCount, $savedAnswers)
{

    $count = 0;
    for ($i = 1; $i <= $medicineCount; $i++) {
        $coverId = 'Cover' . $i;

        $savedCovers = array_filter($savedAnswers, function ($answer) use ($prescriptionId, $status, $coverId) {
            return $answer['pres_id'] === $prescriptionId && $answer['answer_status'] === $status && $answer['cover_id'] === $coverId;
        });

        $count += count($savedCovers);
    }
    return $count;
}


function GetSubmittedAnswersCountByCoverId($loggedUser, $prescriptionId, $status, $coverId, $savedAnswers)
{

    $count = 0;
    $savedCovers = array_filter($savedAnswers, function ($answer) use ($prescriptionId, $status, $coverId) {
        return $answer['pres_id'] === $prescriptionId && $answer['answer_status'] === $status && $answer['cover_id'] === $coverId;
    });

    $count += count($savedCovers);

    return $count;
}
// -----------------------------------------------


function GetPOSProducts()
{
    global $pos_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `product_id`, `product_code`, `ProductName`, `DisplayName`, `PrintName`, `SectionID`, `DepartmentID`, `CategoryID`, `BrandId`, `UOMeasurement`, `ReOderLevel`, `LeadDays`, `CostPrice`, `SellingPrice`, `MinimumPrice`, `WholesalePrice`, `ItemType`, `ItemLocation`, `ImagePath`, `CreatedBy`, `CreatedAt`, `active_status`, `GenericID` FROM `master_product` WHERE `active_status` LIKE 1";
    $result = $pos_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row['product_id'];
        }
    }

    return $ArrayResult;
}

function GetPOSProductDetailsAll()
{
    global $pos_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `product_id`, `product_code`, `ProductName`, `DisplayName`, `PrintName`, `SectionID`, `DepartmentID`, `CategoryID`, `BrandId`, `UOMeasurement`, `ReOderLevel`, `LeadDays`, `CostPrice`, `SellingPrice`, `MinimumPrice`, `WholesalePrice`, `ItemType`, `ItemLocation`, `ImagePath`, `CreatedBy`, `CreatedAt`, `active_status`, `GenericID` FROM `master_product`";
    $result = $pos_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetPOSProductDetails($productId)
{
    global $pos_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT `product_id`, `product_code`, `ProductName`, `DisplayName`, `PrintName`, `SectionID`, `DepartmentID`, `CategoryID`, `BrandId`, `UOMeasurement`, `ReOderLevel`, `LeadDays`, `CostPrice`, `SellingPrice`, `MinimumPrice`, `WholesalePrice`, `ItemType`, `ItemLocation`, `ImagePath`, `CreatedBy`, `CreatedAt`, `active_status`, `GenericID` FROM `master_product` WHERE `product_id` LIKE '$productId'";
    $result = $pos_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['product_id']] = $row;
        }
    }

    return $ArrayResult;
}


function deletePrescriptionAnswerSubmissions($entryId)
{
    global $pdo;  // Assuming $lms_link is your PDO connection

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Add your DELETE query for prescription_answer_submission here
        $deleteQuery = "DELETE FROM `prescription_answer_submission` WHERE `id` = :entryId";
        $deleteStatement = $pdo->prepare($deleteQuery);
        $deleteStatement->bindParam(':entryId', $entryId, PDO::PARAM_INT);
        $deleteResult = $deleteStatement->execute();

        // Check if the deletion was successful
        if ($deleteResult) {
            $result = ['status' => 'success', 'message' => 'Prescription answer submissions deleted successfully'];
        } else {
            $result = ['status' => 'error', 'message' => 'Error deleting records from prescription_answer_submission: ' . $deleteStatement->errorInfo()[2]];
        }

        // If everything is successful, commit the transaction
        $pdo->commit();

        return json_encode($result);
    } catch (Exception $e) {
        // If an error occurs, roll back the transaction
        $pdo->rollBack();

        $result = ['status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . $e->getMessage()];
        return json_encode($result);
    }
}


// 

function GetLinkedProducts()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `linked_products`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['pos_id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetLinkedProductsItem()
{
    global $lms_link;
    $ArrayResult = array();

    // Get Default Course
    $sql = "SELECT * FROM `linked_products`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['pos_id']] = $row['pos_id'];
        }
    }

    return $ArrayResult;
}

function SaveProductLInk($data)
{
    global $lms_link;
    $lms_linkedProducts = GetLinkedProducts();
    $currentTime = date("Y-m-d H:i");

    // Sanitize data to prevent SQL injection
    foreach ($data as $key => $value) {
        if ($key != "methodList" && $key != 'ageList' && $key != 'instructionList') {
            $data[$key] = $lms_link->real_escape_string($value);
        }
    }

    $methodList = json_decode($data["methodList"], true); // Decoding JSON string to array
    $ageList = json_decode($data["ageList"], true); // Decoding JSON string to array
    $instructionList = json_decode($data["instructionList"], true); // Decoding JSON string to array

    $methodListArray = implode(', ', $methodList);
    $ageListArray = implode(', ', $ageList);
    $instructionListArray = implode(', ', $instructionList);

    // Check if the record already exists based on 'id'
    if (isset($lms_linkedProducts[$data['posProductId']])) {
        // Update the record
        $updateQuery = "UPDATE `linked_products` SET
                        `pos_id` = '{$data['posProductId']}',
                        `product_name` = '{$data['productName']}',
                        `method_list` = '{$methodListArray}',
                        `instruction_list` = '{$instructionListArray}',
                        `age_groups` = '{$ageListArray}',
                        `created_by` = '{$data['LoggedUser']}',
                        `update_time` = '{$currentTime}',
                        `is_active` = '{$data['isActive']}'
                        WHERE `pos_id` = '{$data['posProductId']}'";

        $result = $lms_link->query($updateQuery);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . $lms_link->error]);
        }
    } else {
        // Insert a new record
        $insertQuery = "INSERT INTO `linked_products` (`pos_id`, `product_name`, `method_list`, `instruction_list`, `age_groups`, `created_by`, `update_time`, `is_active` )
                        VALUES (
                            '{$data['posProductId']}',
                            '{$data['productName']}',
                            '{$methodListArray}',
                            '{$instructionListArray}',
                            '{$ageListArray}',
                            '{$data['LoggedUser']}',
                            '{$currentTime}',
                            '{$data['isActive']}'
                        )";

        $result = $lms_link->query($insertQuery);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Product Saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting record: ' . $lms_link->error]);
        }
    }

    $lms_link->close();
}
