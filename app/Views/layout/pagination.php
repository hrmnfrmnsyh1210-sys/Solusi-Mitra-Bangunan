<?php
/**
 * Pagination partial
 * Requires: $pager (CI4 Pager), $total (int), optionally $search/$dari/$sampai
 */
if (!isset($pager) || $pager === null) return;
$currentPage = $pager->getCurrentPage();
$totalPages  = $pager->getPageCount();
if ($totalPages <= 1) return;

// Build extra query string from passed vars
$extra = [];
if (!empty($search))  $extra['search'] = $search;
if (!empty($dari))    $extra['dari'] = $dari;
if (!empty($sampai))  $extra['sampai'] = $sampai;
if (!empty($kategori)) $extra['kategori'] = $kategori;

function buildPageUrl($page, $extras) {
    $q = array_merge($extras, ['page' => $page]);
    return '?' . http_build_query($q);
}
?>

<div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-3 border-t border-slate-100 bg-white">
    <p class="text-xs text-slate-400">
        Halaman <strong class="text-slate-600"><?= $currentPage ?></strong> dari
        <strong class="text-slate-600"><?= $totalPages ?></strong>
        &bull; <strong class="text-slate-600"><?= number_format($total) ?></strong> data
    </p>
    <div class="flex items-center gap-1">
        <?php if ($currentPage > 1): ?>
        <a href="<?= buildPageUrl(1, $extra) ?>"
           class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
            <i class="fas fa-angles-left"></i>
        </a>
        <a href="<?= buildPageUrl($currentPage - 1, $extra) ?>"
           class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
            <i class="fas fa-angle-left"></i>
        </a>
        <?php endif; ?>

        <?php
        $start = max(1, $currentPage - 2);
        $end   = min($totalPages, $currentPage + 2);
        for ($p = $start; $p <= $end; $p++):
        ?>
        <a href="<?= buildPageUrl($p, $extra) ?>"
           class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold transition
                  <?= $p === $currentPage
                      ? 'bg-indigo-600 text-white border border-indigo-600'
                      : 'border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            <?= $p ?>
        </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
        <a href="<?= buildPageUrl($currentPage + 1, $extra) ?>"
           class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
            <i class="fas fa-angle-right"></i>
        </a>
        <a href="<?= buildPageUrl($totalPages, $extra) ?>"
           class="w-7 h-7 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs transition">
            <i class="fas fa-angles-right"></i>
        </a>
        <?php endif; ?>
    </div>
</div>
