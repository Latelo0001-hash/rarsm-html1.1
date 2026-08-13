<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/activities.php';

$requestedId = isset($_GET['event']) ? trim((string) $_GET['event']) : '';
$selected = rarsm_get_activity($requestedId);
$notFound = $selected === null;

if ($selected === null) {
    $selected = rarsm_default_activity();
}

$categories = rarsm_activity_categories();
$category = $categories[$selected['category']] ?? ['label' => 'Activité', 'class' => 'event-launch'];
$relatedActivities = rarsm_related_activities($selected['id'], 4);

rarsm_page_head(
    'RARSM | ' . $selected['title'],
    $selected['summary'],
    'activities-detail-page'
);
rarsm_render_header('activites');
rarsm_render_page_title('Détail événement', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Activités', 'href' => 'activites.html'],
    ['label' => $selected['title']],
]);
?>

<section class="ls ms s-py-90 s-py-xl-150 section-activities-detail-page">
    <div class="container">
        <?php if ($notFound): ?>
            <div class="activities-detail-alert">
                L'activité demandée n'a pas été trouvée. La prochaine activité disponible a été affichée par défaut.
            </div>
        <?php endif; ?>

        <div class="row c-gutter-30">
            <div class="col-lg-8">
                <article class="activities-detail-main-card">
                    <span class="activities-calendar-label">Événement sélectionné</span>
                    <div class="activities-detail-hero">
                        <div class="activities-detail-copy">
							<span class="activities-detail-pill <?php echo rarsm_e($category['class']); ?>" data-activity-category="<?php echo rarsm_e($selected['category']); ?>">
                                <?php echo rarsm_e($category['label']); ?>
                            </span>
                            <?php if (!empty($selected['status_label'])): ?>
                                <span class="activities-verification-pill status-<?php echo rarsm_e($selected['verification_status'] ?? 'watch'); ?>">
                                    <?php echo rarsm_e($selected['status_label']); ?>
                                </span>
                            <?php endif; ?>
                            <h2><?php echo rarsm_e($selected['title']); ?></h2>
                            <p class="activities-detail-lead"><?php echo rarsm_e($selected['summary']); ?></p>
                            <div class="activities-detail-meta-row">
                                <span><?php echo rarsm_e(ucfirst(rarsm_activity_display_date($selected))); ?></span>
                                <span><?php echo rarsm_e($selected['time']); ?></span>
                                <span><?php echo rarsm_e($selected['location']); ?></span>
                                <span><?php echo rarsm_e($selected['institution']['name']); ?></span>
                            </div>
                        </div>
                        <div class="activities-detail-media">
                            <figure>
                                <img src="<?php echo rarsm_e($selected['image']); ?>" alt="<?php echo rarsm_e($selected['image_alt']); ?>" loading="lazy" decoding="async">
                                <?php if (!empty($selected['image_credit'])): ?>
                                    <figcaption>
                                        <?php if (!empty($selected['image_credit_url'])): ?>
                                            <a href="<?php echo rarsm_e($selected['image_credit_url']); ?>" target="_blank" rel="noopener">
                                                <?php echo rarsm_e($selected['image_credit']); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo rarsm_e($selected['image_credit']); ?>
                                        <?php endif; ?>
                                    </figcaption>
                                <?php endif; ?>
                            </figure>
                        </div>
                    </div>

					<div class="activities-detail-section activities-detail-section--about">
                        <h4>À propos de cet événement</h4>
                        <p><?php echo rarsm_e($selected['description']); ?></p>
                    </div>

					<div class="activities-detail-section activities-detail-section--organizer">
                        <h4>Organisateur ou cadre de référence</h4>
                        <p><?php echo rarsm_e($selected['institution_role']); ?></p>
                    </div>

                    <?php if (!empty($selected['recurrence_note'])): ?>
						<div class="activities-detail-section activities-detail-section--recurrence">
                            <h4><?php echo ($selected['verification_status'] ?? 'confirmed') === 'confirmed' ? 'Récurrence annuelle' : 'Statut de récurrence'; ?></h4>
                            <p><?php echo rarsm_e($selected['recurrence_note']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($selected['reference'])): ?>
						<div class="activities-detail-section activities-detail-section--source">
                            <h4>Source officielle</h4>
                            <p>
                                <?php if (!empty($selected['reference_url'])): ?>
                                    <a href="<?php echo rarsm_e($selected['reference_url']); ?>" target="_blank" rel="noopener">
                                        <?php echo rarsm_e($selected['reference']); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo rarsm_e($selected['reference']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>

					<div class="activities-detail-section activities-detail-section--highlights">
                        <h4>Points clés</h4>
                        <ul class="activities-detail-agenda-list">
                            <?php foreach ($selected['highlights'] as $highlight): ?>
                                <li><?php echo rarsm_e($highlight); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="activities-detail-cta">
                        <a href="activites.html#<?php echo rarsm_e($selected['id']); ?>" class="btn btn-outline-maincolor">Retour au calendrier</a>
                        <a href="contact.html" class="btn btn-maincolor">Contacter l'équipe</a>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <aside class="activities-detail-sidebar">
                    <div class="activities-detail-side-card activities-institution-card">
                        <span class="activities-calendar-label">Organisateur</span>
                        <div class="activities-institution-head">
                            <div class="activities-institution-logo">
                                <?php echo rarsm_e(rarsm_activity_initials($selected['institution']['name'])); ?>
                            </div>
                            <div class="activities-institution-copy">
                                <h4><?php echo rarsm_e($selected['institution']['full_name']); ?></h4>
                                <p><?php echo rarsm_e($selected['institution']['summary']); ?></p>
                            </div>
                        </div>

                        <div class="activities-social-links">
                            <?php foreach ($selected['institution']['socials'] as $social): ?>
                                <a class="activities-social-link" href="<?php echo rarsm_e($social['href']); ?>" target="_blank" rel="noopener">
                                    <i class="fa <?php echo rarsm_e($social['icon']); ?>" aria-hidden="true"></i>
                                    <span><?php echo rarsm_e($social['label']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <div class="activities-institution-actions">
                            <a href="<?php echo rarsm_e($selected['institution']['details_href']); ?>" class="btn btn-outline-maincolor">Voir l'organisateur</a>
                            <a href="<?php echo rarsm_e($selected['institution']['directory_href']); ?>" class="btn btn-outline-maincolor">Retour au calendrier</a>
                        </div>
                    </div>

                    <div class="activities-detail-side-card">
                        <span class="activities-calendar-label">Autres événements</span>
                        <h4>À suivre aussi</h4>
                        <ul class="activities-related-list list-unstyled">
                            <?php foreach ($relatedActivities as $activity): ?>
                                <li>
                                    <a class="activities-related-item" href="activites-details.php?event=<?php echo rarsm_e($activity['id']); ?>">
                                        <strong><?php echo rarsm_e($activity['title']); ?></strong>
                                        <span><?php echo rarsm_e(ucfirst(rarsm_activity_display_date($activity))); ?> · <?php echo rarsm_e($activity['location']); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<?php rarsm_render_footer(); ?>
