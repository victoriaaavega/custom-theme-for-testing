<?php
$startTime = microtime(true);

// Bootstrap all classes and run experiments before any output is sent
require_once get_template_directory() . '/includes/ExperimentRunner.php';

// Create the backup table if it doesn't exist yet
$database = new Database();
$database->maybeCreateTable();

// Initialize the runner with the simulator adapter
// In production, swap SimulatorAdapter for FlagshipAdapter
$runner = new ExperimentRunner(new SimulatorAdapter());

// Run both experiments independently
$heroExperiment   = $runner->run('experiment_hero');
$navbarExperiment = $runner->run('experiment_navbar');

$heroVariant   = $heroExperiment['variant'];
$navbarVariant = $navbarExperiment['variant'];
$visitorId     = $heroExperiment['visitorId'];
$heroSource    = $heroExperiment['source'];
$navbarSource  = $navbarExperiment['source'];

$t1 = microtime(true);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>

    <?php // Base theme styles ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

    <?php // Load only the CSS for the assigned hero variant ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/<?php echo $heroVariant === 'control' ? 'variant-control' : 'variant-b'; ?>.css">
</head>
<body>

    <?php
    // Experiment 1: Hero section
    if ($heroVariant === 'control') {
        require get_template_directory() . '/templates/variant-control.php';
    } else {
        require get_template_directory() . '/templates/variant-b.php';
    }
    ?>

    <?php // Experiment 2: Navbar variant ?>
    <nav class="navbar">
        <?php if ($navbarVariant === 'control'): ?>
            <a href="#">Iniciar sesión</a>
        <?php else: ?>
            <a href="#">¡Únete gratis!</a>
        <?php endif; ?>
    </nav>

    <div class="debug-info">
        <p>Visitor ID: <code><?php echo $visitorId; ?></code></p>
        <p>Experiment Hero → Variant: <code><?php echo $heroVariant; ?></code> | Source: <code><?php echo $heroSource; ?></code></p>
        <p>Experiment Navbar → Variant: <code><?php echo $navbarVariant; ?></code> | Source: <code><?php echo $navbarSource; ?></code></p>
    </div>

    <?php // Heap identity sync ?>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/heap-sync.js"></script>

    <?php
    $endTime = microtime(true);
    echo '<p style="color:black;padding:10px;">';
    echo 'Total: '          . round(($endTime - $startTime) * 1000) . 'ms<br>';
    echo 'PHP logic: '      . round(($t1 - $startTime) * 1000) . 'ms';
    echo '</p>';
    ?>

</body>
</html>