#!/bin/bash
echo '⚠️ Melakukan Rollback...'
cp storage/backups/seo_patch_20261107_094432/facility-detail.blade.php resources/views/site/facility-detail.blade.php
cp storage/backups/seo_patch_20261107_094432/post-detail.blade.php resources/views/site/post-detail.blade.php
cp storage/backups/seo_patch_20261107_094432/project-detail.blade.php resources/views/site/project-detail.blade.php
cp storage/backups/seo_patch_20261107_094432/extracurricular-detail.blade.php resources/views/site/extracurricular-detail.blade.php
cp storage/backups/seo_patch_20261107_094432/brosur-detail.blade.php resources/views/site/brosur-detail.blade.php
cp storage/backups/seo_patch_20261107_094432/agenda-detail.blade.php resources/views/site/agenda-detail.blade.php
