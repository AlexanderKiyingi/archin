<?php
/**
 * Migration Script: Create job_openings table
 * Run this once to create the job_openings table
 */

require_once 'db_connect.php';

echo "Starting migration...\n";

// Create job_openings table
$create_table = "CREATE TABLE IF NOT EXISTS job_openings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    employment_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') DEFAULT 'Full-time',
    location VARCHAR(200) DEFAULT 'Kampala, Uganda',
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    responsibilities TEXT,
    salary_range VARCHAR(100),
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    posted_date DATE NOT NULL,
    application_deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_posted_date (posted_date)
)";

if ($conn->query($create_table)) {
    echo "✓ job_openings table created successfully\n";
} else {
    echo "✗ Error creating table: " . $conn->error . "\n";
}

// Check if table is empty
$count_result = $conn->query("SELECT COUNT(*) as count FROM job_openings");
$count = $count_result->fetch_assoc()['count'];

if ($count == 0) {
    echo "Inserting sample job openings...\n";
    
    // Insert sample jobs
    $jobs = [
        [
            'title' => 'Senior Architect',
            'employment_type' => 'Full-time',
            'location' => 'Kampala, Uganda',
            'description' => 'Lead architectural projects from concept to completion. Work with clients and design teams to create innovative solutions.',
            'requirements' => '5+ years of experience in architecture|Licensed architect|Proficiency in AutoCAD, Revit, and SketchUp|Strong portfolio of completed projects|Excellent communication and leadership skills',
            'responsibilities' => 'Lead design team and coordinate with clients|Develop architectural concepts and detailed drawings|Ensure compliance with building codes|Mentor junior architects',
            'posted_date' => date('Y-m-d'),
            'status' => 'active'
        ],
        [
            'title' => 'Interior Designer',
            'employment_type' => 'Full-time',
            'location' => 'Kampala, Uganda',
            'description' => 'Create beautiful and functional interior spaces for residential and commercial projects.',
            'requirements' => 'Bachelor\'s degree in Interior Design|3+ years of experience|Proficiency in 3D visualization software|Strong understanding of materials and finishes|Creative portfolio',
            'responsibilities' => 'Develop interior design concepts|Select materials, furniture, and fixtures|Create mood boards and presentations|Work with contractors and suppliers',
            'posted_date' => date('Y-m-d'),
            'status' => 'active'
        ],
        [
            'title' => 'Project Manager',
            'employment_type' => 'Full-time',
            'location' => 'Kampala, Uganda',
            'description' => 'Oversee construction projects from planning through completion, ensuring timely delivery and quality standards.',
            'requirements' => 'Bachelor\'s degree in Architecture or Construction Management|5+ years of project management experience|Strong organizational and leadership skills|Budget management experience|Excellent problem-solving abilities',
            'responsibilities' => 'Coordinate project timelines and resources|Manage budgets and contracts|Communicate with clients and stakeholders|Ensure quality control and compliance',
            'posted_date' => date('Y-m-d'),
            'status' => 'active'
        ],
        [
            'title' => 'Junior Architect',
            'employment_type' => 'Full-time',
            'location' => 'Kampala, Uganda',
            'description' => 'Entry-level position for recent graduates. Work alongside senior architects on exciting projects while developing your skills.',
            'requirements' => 'Bachelor\'s degree in Architecture|0-2 years of experience|Basic knowledge of AutoCAD and design software|Strong design fundamentals|Willingness to learn',
            'responsibilities' => 'Assist with design development|Prepare architectural drawings|Conduct site visits|Support senior architects with project tasks',
            'posted_date' => date('Y-m-d'),
            'status' => 'active'
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO job_openings (title, employment_type, location, description, requirements, responsibilities, posted_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($jobs as $job) {
        $stmt->bind_param("ssssssss", 
            $job['title'], 
            $job['employment_type'], 
            $job['location'], 
            $job['description'], 
            $job['requirements'], 
            $job['responsibilities'], 
            $job['posted_date'], 
            $job['status']
        );
        
        if ($stmt->execute()) {
            echo "✓ Inserted: " . $job['title'] . "\n";
        } else {
            echo "✗ Error inserting " . $job['title'] . ": " . $stmt->error . "\n";
        }
    }
    
    $stmt->close();
} else {
    echo "Table already contains data. Skipping sample data insertion.\n";
}

echo "\nMigration completed!\n";
echo "You can now access the job openings management page.\n";

$conn->close();

