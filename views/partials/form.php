<?php if(!empty($errors)){ ?>
        <div class="alert alert-danger">

            <?php foreach($errors as $error): ?>
            <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php } ?>
    

    <form action="" method="post" enctype="multipart/form-data">

        <?php if ($boardgame['image']){?>
            <img src="/<?php echo $boardgame['image']?>" class="update-image">

        <?php } ?>

        <div class="mb-3">
            <label >Image</label>
            <br>
            <input type="file" name="image" >
        </div>
        <div class="mb-3">
            <label >Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="baseOrExp-base" name="baseOrExp">
            <label class="form-check-label" for="baseOrExp-base">
                Base
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="baseOrExp-Exp" name="baseOrExp">
            <label class="form-check-label" for="baseOrExp-exp">
                Expansion
            </label>
        </div>

        <div class="mb-3">
            <label >Minimum Players</label>
            <input type="Number" step="1" class="form-control" name="minPlayers" value="<?php echo $minPlayers; ?>">
        </div>

        <div class="mb-3">
            <label >Maximum Players</label>
            <input type="Number" step="1" class="form-control" name="maxPlayers" value="<?php echo $maxPlayers; ?>">
        </div>

        <div class="mb-3">
            <label >Minimum Time</label>
            <input type="Number" step="1" class="form-control" name="minTime" value="<?php echo $minTime; ?>">
        </div>

        <div class="mb-3">
            <label >Maximum Time</label>
            <input type="Number" step="1" class="form-control" name="maxTime" value="<?php echo $maxTime; ?>">
        </div>

        <div class="mb-3">
            <label >Owner</label>
            <input type="text" class="form-control" name="owner" value="<?php echo $owner; ?>">
        </div>

        <div class="mb-3">
            <label >Library</label>
            <input type="text" class="form-control" name="library" value="<?php echo $library; ?>">
        </div>

        <?php if(!$redundant){ ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="redundant"  id="redundant">
                <label class="form-check-label" for="redundant">
                    Redundant
                </label>
            </div>
        <?php }
        else{ ?>
                    <div class="form-check">
                <input class="form-check-input" type="checkbox" name="redundant"  id="redundant" checked>
                <label class="form-check-label" for="redundant">
                    Redundant
                </label>
            </div>
        <?php } ?>

        <div class="mb-3">
            <label > Description</label>
            <textarea class="form-control" name="description"><?php echo $description; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


