@if(View::exists("{$viewBasePath}.settings.{$settingGroup}"))
    @include("{$viewBasePath}.settings.{$settingGroup}", ['settingGroup' => $settingGroup])
@endif

@extends("{$layoutBasePath}.default")
