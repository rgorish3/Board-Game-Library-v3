<!--SEARCH GROUP FOR FILTERING GAMES-->
        <div>
            <form>
                
                <!--LEFT SIDE-->

                <div class="row">
                    <div class="col-md-6">
                        <div >
                            
                            <div class="mb-3">
                                <div class="form-group">
                                    <input type="Number" step="1" class="form-control" placeholder="Number of Players" name="numPlayers" value="<?php echo $numPlayers; ?>">
                                </div>
                            </div>

                            <!--TIME MODE - Approximate time is within min or max time. Max time means max time or under. Without input, 
                                default state is Approximate. Otherwise, wage will select whatever was selected when the search button was clicked.
                            -->
                            <div class="mb-3">
                                <div class="form-group">
                                    <?php if($timeMode === 'max'){ ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="approx" value="approx">
                                            <label class="form-check-label" for="approx">
                                                Approximate Time
                                            </label>
                                            
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="max" value="max" checked>
                                            <label class="form-check-label" for="max">
                                                Maximum Time
                                            </label>
                                        </div>
                                    <?php }
                                    else{?>
                                    
                                    <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="approx" value="approx" checked>
                                            <label class="form-check-label" for="approx">
                                                Approximate Time
                                            </label>
                                            
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="max" value="max" >
                                            <label class="form-check-label" for="max">
                                                Maximum Time
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!--END TIME MODE-->

                            <div class="mb-3">
                                <div class="form-group">  
                                    <input type="Number" step="1" class="form-control" placeholder="Time (Minutes)" name="time" value="<?php echo $time; ?>">
                                </div>
                            </div>

                            <!--REDUNDANT EPANSIONS-->
                            <div class="mb-3">
                                <?php if($redundant === "on"){ ?>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="redundant" id="redundant" checked>
                                        <label class="form-check-label" for="redundant">Redundant Expansions</label>
                                    </div>

                                <?php }
                                else{ ?>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="redundant" id="redundant">
                                        <label class="form-check-label" for="redundant">Redundant Expansions</label>
                                    </div>
                                <?php } ?>
                            </div>

                            <!--END REDUNDANT EXPANSIONS-->

                            <div class="mb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo $search; ?>">
                                </div>
                            </div>

                            
                           
                        </div>
                    </div>
                    <!--END LEFT SIDE-->

                    <!--SPACER-->
                    <div class ="col-md-1"></div>
                    <!--END SPACER-->
                    
                    
                    <!--RIGHT SIDE-->
                    
                   
                    <div class="col-md-5">

                        <h3>Libraries</h3>
                        
                        <?php foreach($libraries as $i => $library) : ?>
                               
                            <?php if(in_array($library['Library'], $libraryPassed, $strict=false) || empty($_GET)){ ?>  <!-- Checking to see whether the library is was passed in GET. If it
                                                                                                                            was, displays checkbox as checked. 
                                                                                                                            
                                                                                                                            Will also check to see if $_GET is empty and will use checked version 
                                                                                                                            for that as well. This is intended to have all libraries selected on 
                                                                                                                            initial page load as n$_GET should be empty without a query string.
                                                                                                                            However, I am a little concerned this may be dirty. Testing is required.
                                                                                                                        -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="library[]" value="<?php echo $library['Library']?>"  id="<?php echo $library['Library']?>" checked>
                                    <label class="form-check-label" for="<?php echo $library['Library']?>">
                                        <?php echo $library['Library']; ?>
                                    </label>
                                </div>
                            <?php }
                            else{ ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="library[]" value="<?php echo $library['Library']?>"  id="<?php echo $library['Library']?>">
                                    <label class="form-check-label" for="<?php echo $library['Library']?>">
                                        <?php echo $library['Library']; ?>
                                    </label>
                                </div>
                        
                            <?php } ?>
                        
                        <?php endforeach;?>

                    </div>

                    <!--END RIGHT SIDE-->

                    <div class="mb-3 mt-3">
                        <div class="form-group">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>