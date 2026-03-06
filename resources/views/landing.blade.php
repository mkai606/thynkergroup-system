<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('landing/favicon.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sidekicks Marketing by Thynker — authentic word-of-mouth at scale with 1000+ Sidekicks. Brand awareness, product reviews, and engagement." />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thynker — Sidekicks Marketing</title>
    <script type="module" crossorigin src="{{ asset('landing/assets/index-DxBSBSsz.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('landing/assets/index-DK4VuXE7.css') }}">
  </head>
  <body>
    <div id="root"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for React to render
        setTimeout(function() {
            // Find the "Apply as Sidekick" button
            var applyBtn = null;
            document.querySelectorAll('a').forEach(function(a) {
                if (a.textContent.trim() === 'Apply as Sidekick') applyBtn = a;
            });
            if (!applyBtn) return;

            // Convert <a> to <button> behavior
            applyBtn.addEventListener('click', function(e) {
                e.preventDefault();

                var get = function(id) {
                    var el = document.getElementById(id);
                    return el ? el.value.trim() : '';
                };

                var fullName = get('joinsidekick-fullname');
                var phone = get('joinsidekick-phone');
                var email = get('joinsidekick-email');
                var followers = get('joinsidekick-followers');
                var tiktok = get('joinsidekick-tiktok');
                var instagram = get('joinsidekick-instagram');
                var niche = get('joinsidekick-niche');
                var location = get('joinsidekick-location');
                var avgViews = get('joinsidekick-avgviews');
                var agree = document.getElementById('joinsidekick-agree');
                var fileInput = document.getElementById('joinsidekick-upload');

                // Basic validation
                if (!fullName || !phone || !email || !followers) {
                    alert('Please fill in all required fields (Name, Phone, Email, Followers).');
                    return;
                }
                if (!agree || !agree.checked) {
                    alert('Please agree to the terms before submitting.');
                    return;
                }

                // Collect content types
                var contentTypes = [];
                document.querySelectorAll('[id^="joinsidekick-content-"]').forEach(function(cb) {
                    if (cb.checked) {
                        var label = cb.closest('label');
                        if (label) contentTypes.push(label.textContent.trim());
                    }
                });

                // Build FormData
                var fd = new FormData();
                fd.append('full_name', fullName);
                fd.append('phone', phone);
                fd.append('email', email);
                fd.append('followers', followers);
                fd.append('agree', '1');
                if (tiktok) fd.append('tiktok', tiktok);
                if (instagram) fd.append('instagram', instagram);
                if (niche) fd.append('niche', niche);
                if (location) fd.append('location', location);
                if (avgViews) fd.append('avg_views', avgViews);
                contentTypes.forEach(function(ct) { fd.append('content_types[]', ct); });
                if (fileInput && fileInput.files[0]) fd.append('sample_content', fileInput.files[0]);

                // Disable button
                applyBtn.style.opacity = '0.5';
                applyBtn.style.pointerEvents = 'none';
                applyBtn.textContent = 'Submitting...';

                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/register-sidekick', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: fd
                })
                .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                .then(function(result) {
                    if (result.ok && result.data.success) {
                        // Success — replace form area with success message
                        applyBtn.style.opacity = '1';
                        applyBtn.style.pointerEvents = 'none';
                        applyBtn.textContent = 'Application Submitted!';
                        applyBtn.style.background = '#22c55e';
                        alert(result.data.message);
                    } else {
                        applyBtn.style.opacity = '1';
                        applyBtn.style.pointerEvents = 'auto';
                        applyBtn.textContent = 'Apply as Sidekick';
                        var msg = result.data.message || 'Submission failed. Please try again.';
                        if (result.data.errors) {
                            var errs = Object.values(result.data.errors).flat();
                            msg = errs.join('\n');
                        }
                        alert(msg);
                    }
                })
                .catch(function() {
                    applyBtn.style.opacity = '1';
                    applyBtn.style.pointerEvents = 'auto';
                    applyBtn.textContent = 'Apply as Sidekick';
                    alert('Network error. Please try again.');
                });
            });
        }, 2000); // Wait for React render
    });
    </script>
  </body>
</html>
