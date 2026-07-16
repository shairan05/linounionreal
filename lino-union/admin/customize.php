<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$message = '';
$messageType = '';

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    try {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'setting_') === 0) {
                $settingKey = substr($key, 8);
                $settingValue = is_array($value) ? implode(',', $value) : trim($value);
                updateSetting($settingKey, $settingValue);
            }
        }
        $message = '✅ Settings saved successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = '❌ Error saving settings.';
        $messageType = 'error';
    }
}

require_once __DIR__ . '/includes/admin-header.php';
?>

<style>
:root {
    --color-black: #1A1A1A;
    --color-white: #FFFFFF;
    --color-grey-light: #F8F8F8;
    --color-grey: #E5E5E5;
    --color-grey-dark: #666;
    --color-gold: #C9A96E;
}

.customize-layout { display: flex; gap: 0; min-height: calc(100vh - 120px); }
.customize-sidebar {
    width: 240px;
    flex-shrink: 0;
    background: var(--color-white);
    border: 1px solid var(--color-grey);
    border-right: none;
}
.customize-sidebar .nav-link {
    color: var(--color-black);
    border-radius: 0;
    padding: 0.85rem 1.25rem;
    font-size: 0.85rem;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.customize-sidebar .nav-link i { width: 18px; font-size: 1rem; color: var(--color-grey-dark); }
.customize-sidebar .nav-link:hover { background: var(--color-grey-light); border-left-color: var(--color-grey); }
.customize-sidebar .nav-link.active { background: var(--color-grey-light); border-left-color: var(--color-black); font-weight: 500; }
.customize-sidebar .nav-link.active i { color: var(--color-black); }

.customize-content {
    flex: 1;
    background: var(--color-white);
    border: 1px solid var(--color-grey);
    padding: 2rem;
}
.tab-pane { display: none; }
.tab-pane.active { display: block; }

.setting-group {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--color-grey-light);
}
.setting-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.setting-group-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 1.25rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--color-grey);
}

.setting-row { margin-bottom: 1rem; }
.setting-row label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.35rem;
    color: var(--color-grey-dark);
}
.setting-row .form-control, .setting-row .form-select {
    border-radius: 0;
    border: 1px solid var(--color-grey);
    font-size: 0.9rem;
    padding: 0.55rem 0.85rem;
    transition: border-color 0.2s ease;
}
.setting-row .form-control:focus { box-shadow: none; border-color: var(--color-black); }
.setting-row textarea.form-control { min-height: 80px; resize: vertical; }
.setting-row .input-group .form-control:last-child { border-left: none; }

.preview-image {
    max-width: 160px;
    max-height: 100px;
    object-fit: cover;
    border: 1px solid var(--color-grey);
    margin-top: 0.5rem;
    display: block;
}

.flash-message {
    padding: 0.85rem 1.25rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    border-left: 3px solid var(--color-black);
    background: var(--color-grey-light);
}
.flash-message.success { border-left-color: #2E7D32; background: #E8F5E9; }
.flash-message.error { border-left-color: #C62828; background: #FFEBEE; }

.save-bar {
    position: sticky;
    bottom: 0;
    background: var(--color-white);
    border-top: 1px solid var(--color-grey);
    padding: 1rem 2rem;
    margin: 2rem -2rem -2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.save-bar .btn { padding: 0.65rem 2rem; border-radius: 0; font-size: 0.85rem; }

@media (max-width: 992px) {
    .customize-layout { flex-direction: column; }
    .customize-sidebar { width: 100%; border-right: 1px solid var(--color-grey); }
    .customize-sidebar .nav { display: flex; flex-wrap: nowrap; overflow-x: auto; }
    .customize-sidebar .nav-link { white-space: nowrap; border-left: none; border-bottom: 3px solid transparent; font-size: 0.8rem; padding: 0.65rem 1rem; }
    .customize-sidebar .nav-link.active { border-left-color: transparent; border-bottom-color: var(--color-black); }
}
</style>

<div class="admin-header">
    <div>
        <h1>Site Customization</h1>
        <p class="text-muted mb-0">Customize every section of your website</p>
    </div>
    <a href="../index.php" class="btn btn-outline-dark btn-sm" target="_blank">
        <i class="bi bi-eye me-1"></i> Preview Site
    </a>
</div>

<?php if ($message): ?>
<div class="flash-message <?php echo $messageType; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" id="customizeForm">
    <div class="customize-layout">
        <!-- Sidebar Tabs -->
        <div class="customize-sidebar">
            <div class="nav flex-column nav-pills" id="customizeTabs">
                <button type="button" class="nav-link active" data-tab="general">
                    <i class="bi bi-gear"></i> General
                </button>
                <button type="button" class="nav-link" data-tab="navbar">
                    <i class="bi bi-list"></i> Navbar
                </button>
                <button type="button" class="nav-link" data-tab="hero">
                    <i class="bi bi-images"></i> Hero Slider
                </button>
                <button type="button" class="nav-link" data-tab="collections">
                    <i class="bi bi-grid-3x3-gap"></i> Collections
                </button>
                <button type="button" class="nav-link" data-tab="arrivals">
                    <i class="bi bi-star"></i> New Arrivals
                </button>
                <button type="button" class="nav-link" data-tab="promo">
                    <i class="bi bi-megaphone"></i> Promo Banner
                </button>
                <button type="button" class="nav-link" data-tab="testimonials">
                    <i class="bi bi-chat-quote"></i> Testimonials
                </button>
                <button type="button" class="nav-link" data-tab="newsletter">
                    <i class="bi bi-envelope"></i> Newsletter
                </button>
                <button type="button" class="nav-link" data-tab="footer">
                    <i class="bi bi-layout-three-columns"></i> Footer
                </button>
                <button type="button" class="nav-link" data-tab="colors">
                    <i class="bi bi-palette2"></i> Colors
                </button>
                <button type="button" class="nav-link" data-tab="social">
                    <i class="bi bi-share"></i> Social Media
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="customize-content">
            <?php
            $sections = [
                'general' => 'General Settings',
                'navbar' => 'Navbar',
                'hero' => 'Hero Slider',
                'collections' => 'Collections Section',
                'arrivals' => 'New Arrivals Section',
                'promo' => 'Promo Banner',
                'testimonials' => 'Testimonials',
                'newsletter' => 'Newsletter',
                'footer' => 'Footer',
                'colors' => 'Theme Colors',
                'social' => 'Social Media',
            ];

            $first = true;
            foreach ($sections as $sectionKey => $sectionTitle):
                $settings = getAllSettingsBySection($sectionKey);
                if (empty($settings)) continue;
            ?>
            <div class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="tab-<?php echo $sectionKey; ?>">
                <div class="setting-group">
                    <div class="setting-group-title"><?php echo htmlspecialchars($sectionTitle); ?></div>

                    <?php foreach ($settings as $setting):
                        $key = $setting['setting_key'];
                        $value = htmlspecialchars($setting['setting_value'] ?? '', ENT_QUOTES, 'UTF-8');
                        $label = htmlspecialchars($setting['label'] ?? $key);
                        $type = $setting['setting_type'] ?? 'text';
                        $inputName = 'setting_' . $key;
                    ?>
                    <div class="setting-row">
                        <label for="<?php echo $inputName; ?>"><?php echo $label; ?></label>

                        <?php if ($type === 'textarea'): ?>
                        <textarea class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" rows="3"><?php echo $value; ?></textarea>

                        <?php elseif ($type === 'image'): ?>
                        <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $value; ?>" placeholder="https://... or /path/to/image.jpg">
                            <button class="btn btn-outline-dark" type="button" onclick="document.getElementById('img_<?php echo $inputName; ?>').style.display=this.value?'block':'none'">Preview</button>
                        </div>
                        <?php if ($value): ?>
                        <img src="<?php echo $value; ?>" class="preview-image" id="img_<?php echo $inputName; ?>" onerror="this.style.display='none'">
                        <?php endif; ?>

                        <?php elseif ($type === 'url'): ?>
                        <input type="url" class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $value; ?>">

                        <?php elseif ($type === 'number'): ?>
                        <input type="number" class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $value; ?>" style="max-width:200px;">

                        <?php elseif ($type === 'color'): ?>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color" id="<?php echo $inputName; ?>_picker" value="<?php echo $value; ?>" style="width:48px;height:40px;padding:2px;cursor:pointer;" onchange="document.getElementById('<?php echo $inputName; ?>').value = this.value">
                            <input type="text" class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $value; ?>" placeholder="#HEX or rgba()" style="font-family:monospace;font-size:0.85rem;" oninput="document.getElementById('<?php echo $inputName; ?>_picker').value = this.value">
                        </div>

                        <?php else: ?>
                        <input type="text" class="form-control" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $value; ?>">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $first = false;
            endforeach;
            ?>
        </div>
    </div>

    <!-- Save Bar -->
    <div class="save-bar">
        <span class="text-muted small">Changes are saved to the database and applied site-wide.</span>
        <button type="submit" name="save_settings" class="btn btn-dark">
            <i class="bi bi-check2 me-1"></i> Save All Changes
        </button>
    </div>
</form>

<script>
// Tab switching
document.querySelectorAll('#customizeTabs .nav-link').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#customizeTabs .nav-link').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab)?.classList.add('active');
    });
});

// Live image preview
document.querySelectorAll('input[type="text"][placeholder*="http"]').forEach(input => {
    input.addEventListener('input', function() {
        const preview = document.getElementById('img_' + this.id);
        if (preview) {
            preview.src = this.value || '';
            preview.style.display = this.value ? 'block' : 'none';
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
