<h1>Médiathèque interne</h1>

<p><a href="/resources/create">Ajouter une ressource</a></p>

<?php if (!$resources): ?>
    <p>Aucune ressource pour le moment</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Titre</th><th>Type</th><th>Statut</th><th>Emprunteur</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resources as $resource): ?>
            <tr>
                <td>
                    <a href="/resources/show?id=<?= (int) $resource->id ?>">
                        <?= h($resource->title) ?>
                    </a>
                </td>
                <td><?= h($resource->type) ?></td>
                <td><?= h($resource->status) ?></td>
                <td><?= h($resource->borrower ?? '-') ?></td>
                <td class="actions">
                    <a href="/resources/edit?id=<?= (int) $resource->id ?>">Modifier</a>
                    <form method="post"
                          action="/resources/delete?id=<?= (int) $resource->id ?>"
                          onsubmit="return confirm('Supprimer cette ressource ?')">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>