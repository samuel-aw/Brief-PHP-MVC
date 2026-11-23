<h1><?= h($resource->title) ?></h1>

<table>
    <tr><th>Type</th><td><?= h($resource->type) ?></td></td></tr>
    <tr><th>Statut</th><td><?= h($resource->status) ?></td></tr>
    <tr><th>Emprunteur</th><td><?= h($resource->borrower ?? '-') ?></td></tr>
    <tr><th>Ajoutée le</th><td><?= h($resource->createdAt) ?></td></tr>
</table>

<p class="actions">
    <a href="/resources/edit?id=<?= (int) $resource->id ?>">Modifier</a>
    <a href="/resources">Retour à la liste</a>
</p>