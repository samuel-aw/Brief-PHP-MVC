<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\FlashMessage;
use App\Core\Validator;
use App\Core\View;
use App\Models\Resource;
use App\Repositories\ResourceRepository;

class ResourceController{
    private ResourceRepository $repository;

    public function __construct(){
        $this->repository = new ResourceRepository(Database::getConnection());
    }

    /** GET /resources : liste */
    public function index(): void {
        View::render('resources/index', [
            'pageTitle' => 'Médiathèque interne',
            'resources' => $this->repository->findAll(),
        ]);
    }

    /** GET /resources/show?id=3 : détail */
    public function show(): void {
        $resource = $this->findOrRedirect();

        View::render('resources/show', [
            'pageTitle' => $resource->title,
            'resource' => $resource,
        ]);
    }

    /** GET /resources/create : formulaire d'ajout */
    public function create(): void {
        $this->renderForm(
            'resources/create',
            'Ajouter une ressource',
            new Resource(title: '', type: ''),
            [],
            '/resources/store',
            'Enregistrer'
        );
    }

    /** POST /resources/store : enregistre la nouvella ressource */
    public function store(): void{
        $errors = Validator::validateResource($_POST);
        $resource = Resource::fromArray($_POST);

        if ($errors) {
            http_response_code(422);
            $this->renderForm(
                'resources/create',
                'Ajouter une ressource',
                $resource,
                $errors,
                '/resources/store',
                'Enregistrer'
            );
            return;
        }

        $this->repository->create($resource);

        FlashMessage::set('success', 'Ressource ajoutée');
        redirect('/resources');
    }

    /** GET /resources/edit?id=3 : formulaire de modification */
    public function edit(): void {
        $resource = $this->findOrRedirect();

        $this->renderForm(
            'resources/edit',
            'Modifier une ressource',
            $resource,
            [],
            '/resources/update?id=' . $resource->id,
            'Metrre à jour'
        );
    }

    /** POST /resources/update?id=3 : enregistre la modification */
    public function update(): void{
        $existing = $this->findOrRedirect();

        $errors = Validator::validateResource($_POST);
        $resource = Resource::fromArray(array_merge($_POST, ['id' => $existing->id]));

        if ($errors) {
            http_response_code(422);
            $this->renderForù(
                'resources/edit',
                'Modifier une ressource',
                $resource,
                $errors,
                '/resources/update?id=' .$existing->id,
                'Mettre à jour'
            );
            return;
        }

        $this->repository->update($resource);

        FlashMessage::set('success', 'Ressource modifiée.');
        redirect('/resources');
    }

    /** POST /resources/delete?id=3 : supprime */
    public function delete(): void{
        $resource = $this->findOrRedirect();

        $this->repository->delete($resource->id);

        FlashMessage::set('success', 'Ressource supprimée');
        redirect('/resources');
    }

    /**
     * Récupère la ressource dont l'id est dans l'URL (?id=...)
     * Si elle n'existe pas : message d'erreur et retour à la liste
     */
    private function findOrRedirect(): Resource {
        $id = (int) ($_GET['id'] ?? 0);
        $resource = $this->repository->find($id);

        if ($resource === null) {
            FlashMessage::set('error', 'Ressource introuvable');
            redirect('/resources');
        }

        return $resource;
    }

    private function renderForm(
        string $view,
        string $pageTitle,
        Resource $resource,
        array $errors,
        string $formAction,
        string $submitLabel
    ) : void {
        View::render($view, [
            'pageTitle' => $pageTitle,
            'resource' => $resource,
            'errors' => $errors,
            'formAction' => $formAction,
            'submitLabel' => $submitLabel,
        ]);
    }
}