<?php

test('about command', function () {
    $this->artisan('about')
         ->assertExitCode(0);
});
