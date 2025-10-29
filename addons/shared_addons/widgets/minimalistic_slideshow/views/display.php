<div id="msg_slideshow" class="msg_slideshow">
    <div id="msg_wrapper" class="msg_wrapper">
    </div>
    <div id="msg_controls" class="msg_controls"><!-- right has to animate to 15px, default -110px -->
        <a href="#" id="msg_grid" class="msg_grid"></a>
        <a href="#" id="msg_prev" class="msg_prev"></a>
        <a href="#" id="msg_pause_play" class="msg_pause"></a><!-- has to change to msg_play if paused-->
        <a href="#" id="msg_next" class="msg_next"></a>
    </div>
    <div id="msg_thumbs" class="msg_thumbs"><!-- top has to animate to 0px, default -230px -->
        <div class="msg_thumb_wrapper">
            {{ files:listing folder="<?php echo $options['folder_id'];?>" limit="<?php echo $options['limit'];?>"}}
            <a href="#"><img src="{{ url:site }}files/thumb/{{ id }}/210/280/fit" alt="{{ url:site }}files/thumb/{{ id }}/210/280/fit"/></a>
            {{ /files:listing }}
        </div>
        <div class="msg_thumb_wrapper" style="display:none;">
            {{ files:listing folder="<?php echo $options['folder_id'];?>" limit="<?php echo $options['limit'];?>"}}
            <a href="#"><img src="{{ url:site }}files/thumb/{{ id }}/210/280/fit" alt="{{ url:site }}files/thumb/{{ id }}/210/280/fit"/></a>
            {{ /files:listing }}
        </div>
        <a href="#" id="msg_thumb_next" class="msg_thumb_next"></a>
        <a href="#" id="msg_thumb_prev" class="msg_thumb_prev"></a>
        <a href="#" id="msg_thumb_close" class="msg_thumb_close"></a>
        <span class="msg_loading"></span><!-- show when next thumb wrapper loading -->
    </div>
</div>