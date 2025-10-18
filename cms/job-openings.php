<?php
require_once 'config.php';
$page_title = 'Job Openings';
$page_description = 'Manage job positions and career opportunities';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $title = cleanInput($_POST['title']);
            $employment_type = cleanInput($_POST['employment_type']);
            $location = cleanInput($_POST['location']);
            $description = cleanInput($_POST['description']);
            $requirements = cleanInput($_POST['requirements']);
            $responsibilities = cleanInput($_POST['responsibilities']);
            $salary_range = cleanInput($_POST['salary_range']);
            $status = cleanInput($_POST['status']);
            $posted_date = cleanInput($_POST['posted_date']);
            $application_deadline = !empty($_POST['application_deadline']) ? cleanInput($_POST['application_deadline']) : null;
            
            if ($action === 'add') {
                $sql = "INSERT INTO job_openings (title, employment_type, location, description, requirements, responsibilities, salary_range, status, posted_date, application_deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssss", $title, $employment_type, $location, $description, $requirements, $responsibilities, $salary_range, $status, $posted_date, $application_deadline);
                
                if ($stmt->execute()) {
                    $message = 'Job opening added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding job opening: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $sql = "UPDATE job_openings SET title=?, employment_type=?, location=?, description=?, requirements=?, responsibilities=?, salary_range=?, status=?, posted_date=?, application_deadline=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssi", $title, $employment_type, $location, $description, $requirements, $responsibilities, $salary_range, $status, $posted_date, $application_deadline, $id);
                
                if ($stmt->execute()) {
                    $message = 'Job opening updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating job opening: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($conn->query("DELETE FROM job_openings WHERE id = $id")) {
                $message = 'Job opening deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting job opening.';
                $message_type = 'error';
            }
        } elseif ($action === 'toggle_status') {
            $id = (int)$_POST['id'];
            $new_status = cleanInput($_POST['new_status']);
            $sql = "UPDATE job_openings SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_status, $id);
            
            if ($stmt->execute()) {
                $message = 'Status updated successfully!';
                $message_type = 'success';
            }
        }
    }
}

// Get filter
$filter_status = isset($_GET['status']) ? cleanInput($_GET['status']) : 'all';

// Build query
$where = '';
if ($filter_status !== 'all') {
    $where = " WHERE status = '$filter_status'";
}

$jobs = $conn->query("SELECT * FROM job_openings $where ORDER BY posted_date DESC, created_at DESC");

// Get counts
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as count FROM job_openings")->fetch_assoc()['count'],
    'active' => $conn->query("SELECT COUNT(*) as count FROM job_openings WHERE status = 'active'")->fetch_assoc()['count'],
    'closed' => $conn->query("SELECT COUNT(*) as count FROM job_openings WHERE status = 'closed'")->fetch_assoc()['count'],
    'draft' => $conn->query("SELECT COUNT(*) as count FROM job_openings WHERE status = 'draft'")->fetch_assoc()['count'],
];

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<!-- Header Actions -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Job Openings</h1>
        <p class="text-gray-600 mt-1">Manage career opportunities and job positions</p>
    </div>
    <button onclick="showAddModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <i class="la la-plus"></i>
        Add New Job
    </button>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="flex border-b">
        <a href="?status=all" class="px-6 py-3 <?php echo $filter_status === 'all' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-600'; ?> font-medium">
            All Jobs (<?php echo $counts['all']; ?>)
        </a>
        <a href="?status=active" class="px-6 py-3 <?php echo $filter_status === 'active' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-600'; ?> font-medium">
            Active (<?php echo $counts['active']; ?>)
        </a>
        <a href="?status=draft" class="px-6 py-3 <?php echo $filter_status === 'draft' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-600'; ?> font-medium">
            Draft (<?php echo $counts['draft']; ?>)
        </a>
        <a href="?status=closed" class="px-6 py-3 <?php echo $filter_status === 'closed' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-600'; ?> font-medium">
            Closed (<?php echo $counts['closed']; ?>)
        </a>
    </div>
</div>

<!-- Jobs List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <?php if ($jobs->num_rows > 0): ?>
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posted Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applications</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <?php
                    // Get application count for this position
                    $app_count_result = $conn->query("SELECT COUNT(*) as count FROM career_applications WHERE position = '" . $conn->real_escape_string($job['title']) . "'");
                    $app_count = $app_count_result->fetch_assoc()['count'];
                    
                    $status_colors = [
                        'active' => 'bg-green-100 text-green-800',
                        'closed' => 'bg-gray-100 text-gray-800',
                        'draft' => 'bg-yellow-100 text-yellow-800'
                    ];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($job['title']); ?></div>
                            <div class="text-sm text-gray-500 mt-1"><?php echo substr(htmlspecialchars($job['description']), 0, 100); ?>...</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo htmlspecialchars($job['employment_type']); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <i class="la la-map-marker text-gray-400"></i>
                            <?php echo htmlspecialchars($job['location']); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo date('M d, Y', strtotime($job['posted_date'])); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $status_colors[$job['status']]; ?>">
                                <?php echo ucfirst($job['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="careers.php?position=<?php echo urlencode($job['title']); ?>" class="text-orange-600 hover:text-orange-800 font-medium">
                                <?php echo $app_count; ?> applicants
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Quick Status Toggle -->
                                <?php if ($job['status'] === 'active'): ?>
                                    <button onclick="toggleStatus(<?php echo $job['id']; ?>, 'closed')" 
                                            class="text-gray-600 hover:text-gray-800 px-3 py-1 rounded border border-gray-300 text-sm"
                                            title="Close position">
                                        <i class="la la-ban"></i> Close
                                    </button>
                                <?php else: ?>
                                    <button onclick="toggleStatus(<?php echo $job['id']; ?>, 'active')" 
                                            class="text-green-600 hover:text-green-800 px-3 py-1 rounded border border-green-300 text-sm"
                                            title="Activate position">
                                        <i class="la la-check-circle"></i> Activate
                                    </button>
                                <?php endif; ?>
                                
                                <button onclick='editJob(<?php echo json_encode($job); ?>)' 
                                        class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded border border-blue-300 text-sm">
                                    <i class="la la-edit"></i> Edit
                                </button>
                                <button onclick="deleteJob(<?php echo $job['id']; ?>)" 
                                        class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-300 text-sm">
                                    <i class="la la-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="la la-briefcase text-gray-300" style="font-size: 80px;"></i>
            <p class="text-gray-500 mt-4">No job openings found.</p>
            <button onclick="showAddModal()" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg">
                Add Your First Job
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Modal -->
<div id="jobModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Add New Job Opening</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="la la-times text-2xl"></i>
            </button>
        </div>
        
        <form id="jobForm" method="POST" class="p-6">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="jobId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Job Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                    <input type="text" name="title" id="title" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="e.g., Senior Architect">
                </div>
                
                <!-- Employment Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type *</label>
                    <select name="employment_type" id="employment_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                
                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" name="location" id="location" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g., Kampala, Uganda">
                </div>
                
                <!-- Posted Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Posted Date *</label>
                    <input type="date" name="posted_date" id="posted_date" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <!-- Application Deadline -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline</label>
                    <input type="date" name="application_deadline" id="application_deadline"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                
                <!-- Salary Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salary Range</label>
                    <input type="text" name="salary_range" id="salary_range"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g., UGX 3M - 5M/month">
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                              placeholder="Describe the job role and what the position entails..."></textarea>
                </div>
                
                <!-- Requirements -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirements *</label>
                    <textarea name="requirements" id="requirements" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                              placeholder="Enter each requirement on a new line or separated by | (pipe)"></textarea>
                    <p class="text-sm text-gray-500 mt-1">Separate each requirement with | (e.g., "5+ years experience|Bachelor's degree|AutoCAD skills")</p>
                </div>
                
                <!-- Responsibilities -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Responsibilities</label>
                    <textarea name="responsibilities" id="responsibilities" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                              placeholder="Enter each responsibility on a new line or separated by | (pipe)"></textarea>
                    <p class="text-sm text-gray-500 mt-1">Separate each responsibility with | (e.g., "Lead design team|Review drawings|Client meetings")</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal()" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    <i class="la la-save"></i> Save Job Opening
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddModal() {
    document.getElementById('jobModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Add New Job Opening';
    document.getElementById('formAction').value = 'add';
    document.getElementById('jobForm').reset();
    document.getElementById('posted_date').value = '<?php echo date('Y-m-d'); ?>';
}

function editJob(job) {
    document.getElementById('jobModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Job Opening';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('jobId').value = job.id;
    document.getElementById('title').value = job.title;
    document.getElementById('employment_type').value = job.employment_type;
    document.getElementById('location').value = job.location;
    document.getElementById('description').value = job.description;
    document.getElementById('requirements').value = job.requirements;
    document.getElementById('responsibilities').value = job.responsibilities || '';
    document.getElementById('salary_range').value = job.salary_range || '';
    document.getElementById('status').value = job.status;
    document.getElementById('posted_date').value = job.posted_date;
    document.getElementById('application_deadline').value = job.application_deadline || '';
}

function closeModal() {
    document.getElementById('jobModal').classList.add('hidden');
}

function deleteJob(id) {
    if (confirm('Are you sure you want to delete this job opening? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleStatus(id, newStatus) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="action" value="toggle_status">
        <input type="hidden" name="id" value="${id}">
        <input type="hidden" name="new_status" value="${newStatus}">
    `;
    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.getElementById('jobModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>

