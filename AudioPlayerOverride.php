<?php

namespace Vanderbilt\AudioPlayerOverride;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class AudioPlayerOverride extends AbstractExternalModule
{
    function redcap_survey_page($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id = NULL, $repeat_instance = 1) {
        $instrumentList = $this->getProjectSetting('form');
        $autoPlays = $this->getProjectSetting('auto-play');
        $playOnces = $this->getProjectSetting('play-once');
        $browserType = $_SERVER['HTTP_USER_AGENT'];

        $isIEorChrome = false;
        if (preg_match('~MSIE|Internet Explorer~i', $browserType) || (strpos($browserType, 'Trident/7.0') !== false && strpos($browserType, 'rv:11.0') !== false) || strpos($browserType, 'Chrome/') !== false) {
            $isIEorChrome = true;
        }

        if (in_array($instrument,$instrumentList)) {
            $index = array_search($instrument,$instrumentList);
            echo "<script>
                jQuery(document).ready(function() {
                    var getClosest = function(elem) {
                            for ( ; elem && elem !== document; elem = elem.parentNode ) {
                                if ( elem.id != '' ) return elem.id;
                            }
                            return null;
                                                    };
                    const playerTimes = {};
                    const endAudio = {};
                    
                    jQuery('audio').each(function() {
                        var audioElement = this;
                        var parentID = getClosest(this);
                        playerTimes[parentID] = 0;
                        endAudio[parentID] = false;
                    ";
                    if ($autoPlays[$index] == 1 && $isIEorChrome) {
                        echo "jQuery(this).on('canplaythrough', function() {
                            var playPromise = this.play();
                            if (playPromise !== undefined && endAudio[parentID] != true) {
                                playPromise.then(function () {";
                                if ($playOnces[$index] == 1) {
                                    /*echo "
                                       if (localStorage.getItem(parentID) == 'played') {
                                       console.log('here to set endAudio[parentID]');
                                            endAudio[parentID] = true;
                                            audioElement.currentTime = audioElement.duration;
                                            //audioElement.pause();
                                       }
                                       else {
                                        localStorage.setItem(parentID,'played');
                                        endedOnThisPage = true;
                                       }";*/
                                }
                                echo "}).catch(function(error) {
                                    console.log(error);
                                });
                            }
                        });";
                    }
                    if ($playOnces[$index] == 1) {
                        echo "
                       jQuery(this).on('ended', function () {
                        if (this.paused != true) {
                            playerTimes[parentID] = this.duration;
                        }
                        if (this.ended == true) {
                            endAudio[parentID] = true;
                            this.onplaying = function () {
                                jQuery(this).trigger('pause');
                            };
                        }
                       });                      
                        jQuery(this).on('play',function() {
                            this.currentTime = playerTimes[parentID];
                        });
                        jQuery(this).attr('controlsList','nodownload');
                        
                        jQuery(this).on('pause', function() {
                            if (this.ended == false && endAudio[parentID] == false) {
                                jQuery(this).trigger('play');
                            }
                        });
                        
                        jQuery(this).on('timeupdate', function() {
                            if (endAudio[parentID] != true) {
                                if (this.currentTime < playerTimes[parentID] || (this.currentTime - playerTimes[parentID]) > 0.3 || this.paused) {
                                    this.currentTime = playerTimes[parentID];
                                }
                                else {
                                    playerTimes[parentID] = this.currentTime;
                                }
                            }
                        });";
                    }
                    echo "});
                });
            </script>";
        }
    }
}