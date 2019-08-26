<?php

namespace Vanderbilt\AudioPlayerOverride;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class AudioPlayerOverride extends AbstractExternalModule
{
    function redcap_survey_page($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, int $response_id = NULL, $repeat_instance = 1) {
        $instrumentList = $this->getProjectSetting('form');
        $autoPlays = $this->getProjectSetting('auto-play');
        $playOnces = $this->getProjectSetting('play-once');

        if (in_array($instrument,$instrumentList)) {
            $index = array_search($instrument,$instrumentList);
            echo "<script>
                jQuery(document).ready(function() {
                    jQuery('audio').each(function() {";
                    if ($autoPlays[$index] == 1) {
                        echo "var endedAudio = false;
                            var lastTime = 0;
                        jQuery(this).on('canplaythrough', function() {
                            //jQuery(this).attr('autoplay',true);
                            jQuery(this).trigger('play');
                        });";
                    }
                    if ($playOnces[$index] == 1) {
                        echo "jQuery(this).on('ended', function () {
                            endedAudio = true;
                            jQuery(this).on('play', function () {
                                jQuery(this).trigger('pause');
                            })
                        });
                        
                        jQuery(this).attr('controlsList','nodownload');
                        this.onpause = function() {
                            if (this.ended == false && endedAudio == false) {
                                jQuery(this).trigger('play');
                            }
                        };
                        
                        jQuery(this).on('timeupdate', function() {
                            if (endedAudio == true) return false;
                            if (this.currentTime < lastTime || (this.currentTime - lastTime) > 0.5) {
                                this.currentTime = lastTime;
                            }
                            else {
                                lastTime = this.currentTime;
                            }
                        });";
                    }
                    echo "});
                });
            </script>";
        }
    }
}