<?php $this->section('title', 'Liste de vos inscriptions'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-list-alt fa-fw"></i> <?= count($inscriptions) ?> membre(s) inscrits</h5>
            </div>
            <div class="card-body">
                <section class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="active">
                                <th>Profil </th>
                                <th>Progression </th>
                                <th>Date d'inscription </th>
                                <th>Ip et Navigateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inscriptions)) : ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-info m-b-0 mb-0">
                                            Aucune inscription éffectuée
                                        </div>
                                    </td>
                                </tr>
                            <?php else: foreach ($inscriptions as $i) : ?>
                                <tr class="unread">
                                    <td><div class="d-flex align-items-start">
                                        <img class="rounded-circle" style="width:40px;" src="<?= $i->utilisateur->avatar ?>" alt="">
                                        <div class="ml-2">
                                            <h5 class="mb-1"><?= $i->utilisateur->ref ?></h5>
                                            <h6 class="mb-1"><?= $i->utilisateur->tel ?> - <?= $i->utilisateur->user->username ?></h6>
                                        </div>
                                    </div></td>
                                    <td>
                                        Niveau: <span class="font-weight-bold"><?= $i->utilisateur->niveau ?> / 5</span> 
                                        <br> 
                                        Pack: <span class="font-weight-bold"><?= strtoupper($i->utilisateur->pack) ?></span> 
                                    </td>
                                    <td>
                                        Date: <span class="font-weight-bold"><?= $i->created_at ?></span> 
                                        <br> 
                                        Nbr de compte: <span class="font-weight-bold"><?= $i->nbr ?></span> 
                                    </td>
                                    <td>
                                        Adresse IP: <span class="font-weight-bold"><?= $i->ip ?></span> 
                                        <br> 
                                        Navigateur: <span class="font-weight-bold"></span> 
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
				</section>
            </div>
        </div>
    </div>
</div>

<?php $this->end() ?>