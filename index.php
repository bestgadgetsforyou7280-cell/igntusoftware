<?php 
require_once 'includes/header.php'; 

// --- होमपेज के सभी सेक्शन के लिए डेटा प्राप्त करना ---

// आँकड़ों के लिए डेटा
$total_students_result = $conn->query("SELECT COUNT(id) AS total FROM students WHERE status = 'active'");
$total_students = $total_students_result ? $total_students_result->fetch_assoc()['total'] : 0;
$total_faculty_result = $conn->query("SELECT COUNT(id) AS total FROM faculty WHERE status = 'active'");
$total_faculty = $total_faculty_result ? $total_faculty_result->fetch_assoc()['total'] : 0;
$placement_percentage = $settings['placement_percentage'] ?? 95;
$years_of_excellence = $settings['years_of_excellence'] ?? 5;

// झलक (Preview) सेक्शन के लिए डेटा
$faculty_sql = "SELECT * FROM faculty WHERE status='active' ORDER BY name LIMIT 4";
$faculty_result = $conn->query($faculty_sql);

$notices_sql = "SELECT * FROM notices WHERE status='active' ORDER BY notice_date DESC LIMIT 3";
$notices_result = $conn->query($notices_sql);

$recruiters_sql = "SELECT DISTINCT company_name, company_logo FROM placements ORDER BY placement_year DESC LIMIT 6";
$recruiters_result = $conn->query($recruiters_sql);
?>

<!-- ======================================================= -->
<!-- ========= हीरो और फीचर्स सेक्शन (जैसा आप चाहते थे) ========= -->
<!-- ======================================================= -->

<!-- ======================================================= -->
<!-- ========= नया स्लाइडिंग बैनर हीरो सेक्शन ========= -->
<!-- ======================================================= -->
<section class="hero-section">
    <!-- यह कंटेनर स्लाइडिंग इमेज को होल्ड करेगा -->
    <div class="hero-slider">
        <div class="slide active" style="background-image: url('assets/images/banner1.jpg');"></div>
        <div class="slide" style="background-image: url('assets/images/banner2.jpg');"></div>
        <div class="slide" style="background-image: url('assets/images/banner3.jpg');"></div>
    </div>

    

    <!-- ओवरले और टेक्स्ट कंटेंट (जैसा पहले था) -->
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1><?php echo htmlspecialchars($settings['hero_title'] ?? 'Empowering Students with Future-Ready Skills'); ?></h1>
        <p><?php echo htmlspecialchars($settings['hero_subtitle'] ?? 'Your journey into the world of technology starts here.'); ?></p>
        <a href="#features" class="btn btn-primary smooth-scroll">Explore More</a>
    </div>
</section>

<!-- ======================================================= -->
<!-- ========= नया स्क्रॉलिंग अनाउंसमेंट बार ========= -->
<!-- ======================================================= -->
<?php
// Fetch latest 5 "general" or "event" type notices for the ticker
$ticker_sql = "SELECT title, id FROM notices WHERE status='active' AND (notice_type='general' OR notice_type='event') ORDER BY notice_date DESC LIMIT 5";
$ticker_result = $conn->query($ticker_sql);
?>
<div class="announcement-bar">
    <div class="container announcement-container">
        <div class="announcement-label">Latest Updates</div>
        <div class="scrolling-wrapper">
            <div class="scrolling-content">
                <?php if ($ticker_result && $ticker_result->num_rows > 0): ?>
                    <?php while($ticker_item = $ticker_result->fetch_assoc()): ?>
                        <span class="scrolling-item">
                            <i class="fas fa-bullhorn"></i>
                            <a href="notices.php#notice-<?php echo $ticker_item['id']; ?>"><?php echo htmlspecialchars($ticker_item['title']); ?></a>
                        </span>
                    <?php endwhile; ?>
                    <!-- Duplicate for seamless loop -->
                    <?php $ticker_result->data_seek(0); ?>
                    <?php while($ticker_item = $ticker_result->fetch_assoc()): ?>
                         <span class="scrolling-item">
                            <i class="fas fa-bullhorn"></i>
                            <a href="notices.php#notice-<?php echo $ticker_item['id']; ?>"><?php echo htmlspecialchars($ticker_item['title']); ?></a>
                        </span>
                    <?php endwhile; ?>
                <?php else: ?>
                    <span class="scrolling-item">Welcome to the Department of Software Development.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- ========= नया News & Faculty सेक्शन (2-कॉलम) ========= -->
<!-- ======================================================= -->
<section id="news-and-faculty" class="bg-white">
    <div class="container">
        <div class="dual-section-grid">

            <!-- Left Column: Latest Department News -->
            <div class="news-column">
                <h2 class="section-title-left">Latest Department News</h2>
                <div class="news-card-condensed">
                    <?php 
                    if ($notices_result && $notices_result->num_rows > 0):
                        $notices_result->data_seek(0); // Reset result pointer
                        while($notice = $notices_result->fetch_assoc()):
                    ?>
                        <div class="latest-news-item">
                            <div class="news-date">
                                <i class="fas fa-calendar-alt"></i> <?php echo date('d F, Y', strtotime($notice['notice_date'])); ?>
                            </div>
                            <h4 class="news-title"><?php echo htmlspecialchars($notice['title']); ?></h4>
                            <p class="news-excerpt"><?php echo htmlspecialchars(substr($notice['description'], 0, 80)) . '...'; ?></p>
                            <a href="notices.php" class="read-more-link">Read More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <p>No recent department news available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Our Faculty -->
            <!-- ======================================================= -->
            <!-- ========= अपडेटेड Our Faculty सेक्शन (VC और H.O.D. के साथ) ========= -->
            <!-- ======================================================= -->
            <div class="faculty-column">
                <h2 class="section-title-left">Our Faculty</h2>
                
                <!-- VC Card -->
                <div class="hod-card"> <!-- Using same styling as HOD for consistency -->
                    <img src="uploads/faculty/vc.jpg" alt="Vice-Chancellor"> <!-- Replace with actual VC image path -->
                    <div class="hod-info">
                        <h3 class="hod-name">Prof. Byomakesh Tripathy</h3> <!-- Replace with actual VC name -->
                        <p class="hod-designation">Vice-Chancellor</p> <!-- Replace with actual VC designation -->
                    </div>
                </div>
                
                <!-- H.O.D. Card -->
                <div class="hod-card">
                    <img src="uploads/faculty/hod.jpg" alt="Head of Department">
                    <div class="hod-info">
                        <h3 class="hod-name">Dr. Vikash Kumar Singh</h3>
                        <p class="hod-designation">Head & Dean of Department</p>
                    </div>
                </div>
                
                <!-- Guest Faculty List -->
                <div class="faculty-list">
                    <?php if ($faculty_result && $faculty_result->num_rows > 0):
                        $faculty_result->data_seek(0); // Reset result pointer
                        while($faculty = $faculty_result->fetch_assoc()):
                    ?>
                    <a href="faculty.php" class="faculty-list-item">
                        <img src="uploads/faculty/<?php echo htmlspecialchars($faculty['photo'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($faculty['name']); ?>">
                        <div class="faculty-info">
                            <span class="faculty-name"><?php echo htmlspecialchars($faculty['name']); ?></span>
                            <span class="faculty-designation"><?php echo htmlspecialchars($faculty['designation']); ?></span>
                        </div>
                    </a>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <p>Faculty details will be updated soon.</p>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- ======================================================= -->
<!-- ========= नया, प्रीमियम फीचर्स सेक्शन ========= -->
<!-- ======================================================= -->
<section id="features" class="features-section bg-white">
    <div class="container">
        <h2 class="section-title">Why Choose Our Program?</h2>
        <div class="features-grid">
            
            <!-- Feature Card 1: Practical Learning -->
            <div class="feature-card">
                <div class="icon-container">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>Practical Learning</h3>
                <p>Emphasis on hands-on coding experience and lab work to build real-world skills from day one.</p>
            </div>
            
            <!-- Feature Card 2: Industry Projects -->
            <div class="feature-card">
                <div class="icon-container">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h3>Industry Projects</h3>
                <p>Opportunities to work on real-world projects and gain invaluable industry exposure and experience.</p>
            </div>
            
            <!-- Feature Card 3: Modern Tech Stack -->
            <div class="feature-card">
                <div class="icon-container">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Modern Tech Stack</h3>
                <p>Our curriculum is constantly updated with the latest technologies, tools, and frameworks used in the industry.</p>
            </div>
            
            <!-- Feature Card 4: Placement Support -->
            <div class="feature-card">
                <div class="icon-container">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Placement Support</h3>
                <p>A dedicated placement cell providing 100% assistance, career guidance, and interview preparation.</p>
            </div>
            
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <h3 class="counter" data-target="<?php echo $total_students; ?>">0</h3>
                <p>Students Enrolled</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="<?php echo $total_faculty; ?>">0</h3>
                <p>Faculty Members</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="<?php echo $placement_percentage; ?>">0</h3>
                <p>Placement Percentage</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="<?php echo $years_of_excellence; ?>">0</h3>
                <p>Years of Excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================= -->
<!-- ========= अपडेटेड Student Feedback सेक्शन (6 छात्र) ========= -->
<!-- ======================================================= -->
<section id="student-feedback" class="bg-white">
    <div class="container">
        <h2 class="section-title">Student Testimonials</h2>
        <div class="feedback-grid">
            
            <!-- Testimonial 1 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/sumit tiwari .png" alt="Sumit Tiwari" class="profile-pic">
                <h3 class="feedback-name">Sumit Tiwari</h3>
                <p class="feedback-batch">Batch: 2023-2026</p>
                <p class="feedback-quote">"All the best to my dear juniors for creating this platform... At IGNTU, students get full support from both the department and seniors."</p>
                <div class="feedback-rating">⭐⭐⭐⭐⭐</div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/Shailesh singh.jpg" alt="Shailesh Singh" class="profile-pic">
                <h3 class="feedback-name">Shailesh Singh</h3>
                <p class="feedback-batch">Batch: 2022-2025</p>
                <p class="feedback-quote">"I have 4 years of experience in web development and 1 year of industrial experience, thanks to the practical approach of the program."</p>
                <div class="feedback-rating">⭐⭐⭐⭐⭐</div>
            </div>

            <!-- Testimonial 3 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/samriddhi.jpg.jpg" alt="Samriddhi Jain" class="profile-pic">
                <h3 class="feedback-name">Samriddhi Jain</h3>
                <p class="feedback-batch">Batch: 2024-2027</p>
                <p class="feedback-quote">"The faculty explained concepts very clearly. The assignments and internship gave me good exposure to data analysis."</p>
                <div class="feedback-rating">⭐⭐⭐⭐☆</div>
            </div>
            
            <!-- Testimonial 4 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/Naitik Mishra.jpg" alt="Naitik Mishra" class="profile-pic">
                <h3 class="feedback-name">Naitik Mishra</h3>
                <p class="feedback-batch">Batch: 2024-2027</p>
                <p class="feedback-quote">"I have experience in C and C++ programming through problem-solving and coursework, which has been incredibly helpful."</p>
                <div class="feedback-rating">⭐⭐⭐⭐⭐</div>
            </div>

            <!-- Testimonial 5 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/Priyanshu Tiwari.jpeg" alt="Priyanshu Tiwari" class="profile-pic">
                <h3 class="feedback-name">Priyanshu Tiwari</h3>
                <p class="feedback-batch">Batch: 2020-2024</p>
                <p class="feedback-quote">"Successfully completed the High Level Internship, where I worked on practical software development tasks and gained hands-on experience."</p>
                <div class="feedback-rating">⭐⭐⭐⭐⭐</div>
            </div>

            <!-- Testimonial 6 -->
            <div class="feedback-card">
                <img src="uploads/testimonials/AASTHA BHATNAGAR.jpg" alt="Aastha Bhatnagar" class="profile-pic">
                <h3 class="feedback-name">Aastha Bhatnagar</h3>
                <p class="feedback-batch">Batch: 2021-2025</p>
                <p class="feedback-quote">"My learning journey has been full of growth. I’m truly grateful for the strong bonds I’ve built with my seniors, batchmates, and juniors."</p>
                <div class="feedback-rating">⭐⭐⭐⭐⭐</div>
            </div>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>