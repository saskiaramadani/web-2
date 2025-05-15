<?php
/**
 * Layout Helper Functions
 * Provides functions for managing layouts and templates
 */

/**
 * Renders a layout with specified content
 *
 * @param string $layout Layout template name
 * @param array $vars Variables to be passed to the layout
 * @return string The rendered layout
 */
function renderLayout($layout, $vars = [])
{
    // Check if layout file exists
    $layoutFile = __DIR__ . "/../layouts/{$layout}.php";

    if (!file_exists($layoutFile)) {
        trigger_error("Layout file {$layout}.php not found", E_USER_ERROR);
        return false;
    }

    // Extract variables for use in the layout
    extract($vars);

    // Start output buffering
    ob_start();
    include $layoutFile;
    $rendered = ob_get_clean();

    return $rendered;
}

/**
 * Renders content within a layout
 * 
 * @param string $content The content to render
 * @param string $layout The layout to use
 * @param array $vars Additional variables for the layout
 * @return string The rendered page
 */
function renderContent($content, $layout = 'main', $vars = [])
{
    $vars['content'] = $content;
    return renderLayout($layout, $vars);
}

/**
 * Renders a partial view
 * 
 * @param string $partial Partial view name
 * @param array $vars Variables to be passed to partial
 * @return string The rendered partial
 */
function renderPartial($partial, $vars = [])
{
    // Check if partial file exists
    $partialFile = __DIR__ . "/../partials/{$partial}.php";

    if (!file_exists($partialFile)) {
        trigger_error("Partial file {$partial}.php not found", E_USER_ERROR);
        return false;
    }

    // Extract variables for use in the partial
    extract($vars);

    // Start output buffering
    ob_start();
    include $partialFile;
    return ob_get_clean();
}
?>