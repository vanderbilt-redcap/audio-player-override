# Audio Player Override Module - General
Module to affect audio player functionality. Specifically affects replayability and auto playing.

This module currently only affects the functionality of the audio fields within a survey view. It will work with forms having multiple audio fields on screen at once, but the auto play feature will cause them all to play at the same time.

**Issues with Autoplay** - Unfortunately, the ability to autoplay audio clips on a webpage can be prevented by a user's browser. This is the default behavior in Mozilla Firefox and Safari, but not in other browsers. If a user's browser is set up to prevent audio files from autoplaying, there is nothing to be done other than allow the user to initiate the audio playing. A browser preventing autoplay will not prevent any of the other functionality from working.

# Settings
**"Survey Form with Audio Fields"** - 
    Specifies which survey contains audio fields to manipulate in the project. This is a dropdown of forms in the project.
    
**"Audio Fields Can Only Play Once"** -
    Setting to make sure an audio field can only play once. This also includes the module preventing a user from skipping around on the audio timeline or pausing the audio. It also disables the ability for a user to download the audio file.
    
**"Audio Fields Auto Play on Page Load"** -
    This setting causes an audio file to play automatically, if allowed by the user's browser.