<?php if(!empty($errors)){ ?>
        <div class="alert alert-danger">

            <?php foreach($errors as $error): ?>
            <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php } ?>
    

    <form action="" method="post" enctype="multipart/form-data">

        <?php if ($boardgame['imageURL']){?>
            <img src="<?php echo $boardgame['imageURL']?>" class="update-image">

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
       
        
        <!--BASE/EXPANSION-->

        <div class="mb-3">

            <!--BASE CHECKBOX-->

            <?php if(!$baseOrExp_base) { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="baseOrExp_base" name="baseOrExp_base">
                    <label class="form-check-label" for="baseOrExp_base">
                        Base
                    </label>
                </div>
            <?php }
            else { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="baseOrExp_base" name="baseOrExp_base" checked>
                    <label class="form-check-label" for="baseOrExp_base">
                        Base
                    </label>
                </div>
            <?php } ?>

            <!--END BASE CHECKBOX-->

            <!--EXPANSION CHECKBOX --> 

            <?php if(!$baseOrExp_exp) { ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="baseOrExp_exp" name="baseOrExp_exp">
                <label class="form-check-label" for="baseOrExp_exp">
                    Expansion
                </label>
            </div>

            <?php }
            else { ?>
                <input class="form-check-input" type="checkbox" id="baseOrExp_exp" name="baseOrExp_exp" checked>
                <label class="form-check-label" for="baseOrExp_exp">
                    Expansion
                </label>
            <?php } ?>

            <!--END EXPANSION CHECKBOX --> 
        
        </div>
        <!--BASE/EXPANSION-->



        <!--PLAYERS-->

        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="mb-3">
                    <label >Minimum Players</label>
                    <input type="Number" step="1" class="form-control" name="minPlayers" value="<?php echo $minPlayers; ?>">
                </div>
            </div> 
            <div class="col-md-6 col-sm-6">
                <div class="mb-3">
                    <label >Maximum Players</label>
                    <input type="Number" step="1" class="form-control" name="maxPlayers" value="<?php echo $maxPlayers; ?>">
                </div>
            </div> 
        </div>

         <!--END PLAYERS-->

         <!--TIME-->

        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="mb-3">
                    <label >Minimum Time</label>
                    <input type="Number" step="1" class="form-control" name="minTime" value="<?php echo $minTime; ?>">
                </div>
            </div> 
            <div class="col-md-6 col-sm-6">
                <div class="mb-3">
                    <label >Maximum Time</label>
                    <input type="Number" step="1" class="form-control" name="maxTime" value="<?php echo $maxTime; ?>">
                </div>
            </div> 
        </div>

        <!--END TIME-->
        
        <!--OWNER, LIBRARY, LOCATION-->

        <div class="row">
            
            <div class= "col-md-4 col-sm-4">
                <div class="mb-3">
                    <label >Owner</label>
                    <input type="text" class="form-control" name="owner" value="<?php echo $owner; ?>">
                </div>
            </div>
            
            <div class= "col-md-4 col-sm-4">
                <div class="mb-3">
                    <label >Library</label>
                    <input type="text" class="form-control" name="library" value="<?php echo $library; ?>">
                </div>
            </div>   
            
            <div class= "col-md-4 col-sm-4">
                <div class="mb-3">
                    <label >Location</label>
                    <input type="text" class="form-control" name="location" value="<?php echo $location; ?>">
                </div>
            </div>     
        </div> 
        
        <!--END OWNER, LIBRARY, LOCATION-->


        <!--REDUNDANT CHECKBOX -->
        <div class="mb-3">
            <?php if(!$redundant){ ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="redundant"  id="redundant">
                    <label class="form-check-label" for="redundant">
                        Redundant Expansion
                    </label>
                </div>
            <?php }
            else{ ?>
                        <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="redundant"  id="redundant" checked>
                    <label class="form-check-label" for="redundant">
                        Redundant Expansion
                    </label>
                </div>
            <?php } ?>
        </div>
        <!--END REDUNDANT CHECKBOX -->

        <!--PLAYED CHECKBOX -->
        <div class="mb-3">
            <?php if(!$played){ ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="played"  id="played">
                    <label class="form-check-label" for="played">
                        Played
                    </label>
                </div>
            <?php }
            else{ ?>
                        <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="played"  id="played" checked>
                    <label class="form-check-label" for="played">
                        Played
                    </label>
                </div>
            <?php } ?>
        </div>

        <!--END PLAYED CHECKBOX -->

        <div class="mb-3">
            <label > Description</label>
            <textarea class="form-control" name="description"><?php echo $description; ?></textarea>
        </div>
        
        <input type="hidden" name="imageURL" value="<?php echo $url ?>">
        <input type="hidden" name="objectid" value="<?php echo $objectid ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


