<?php

namespace App\Core;

class View{
    /**
     * Affiche une vue à l'intérieur d'un layout
     *
     * @param string $view Chemin de la vue dans views/ (ex. 'resources/index)
     * @param array $data  Données transmises par le contrôleur
     * @param string $layout Layout à utiliser (dans views/)
     */
    public static function render(string $view, array $data = [], string $layout = 'layouts/main'): void{
        // Le message flash est disponible dans toutes les vues
        $data['flash'] = FlashMessage::get();

        $content = self::capture(__DIR__ . '/../../views/' . $view . '.php', $data);

        echo self::capture(
            __DIR__ . '/../../views/' . $layout . '.php',
            $data + ['content' => $content]
        );
    }

    private static function capture(string $file, array $data): string{
        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return ob_get_clean();
    }
}