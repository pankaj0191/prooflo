@extends('email')
@section('content')
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="margin-bottom: 0px;">
        <tr>
            <td style="padding: 40px; padding-bottom: 0px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 16px">
                Hi {{$name}}, 
            </td>
        </tr>
        <tr>
            <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
                You have been invited by <b>{{ $sent_by_name }}</b> to join the project called
                <b>{{$project->name}}</b>.<br> Click the link below to join the project.<br>
                <br>
            </td>
        </tr>
        @if($project->website_url)
            <tr>
                <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
                    Project website URL <br>
                    {{ $project->website_url }}
                </td>
            </tr>
        @endif
        <tr>
            <td valign="middle" align="center" style="-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background-image: url(http://tedgoas.github.io/Cerberus/assets/bg-btn.png); background-position: top left; background-repeat: repeat-x; background-color: #fff;padding-bottom: 30px">
                <a href="{{config('app.url')}}/loadProofer/{{$project->project_hash}}/{{$revision->id}}" target="blank" style="color: #ffffff; font-family: sans-serif; font-size: 15px; line-height: 15px; text-align: center; text-decoration: none; display: block; padding: 15px 20px; border: 1px solid #333333; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background-color: #575959; width: 130px; border: 0px">
                    <b>GO TO PROJECT</b>
                </a>
            </td>
        </tr>
        <tr>
            <td valign="middle" align="center" style="padding: 0 0 25px 0;">
                <a href="{{config('app.url')}}/settings/notifications" target="blank" style="text-decoration: none; font-family: sans-serif; font-size: 11px; color: #80b4a0;">
                    <b>Unsubscribe from Prooflo Emails</b>
                </a>
            </td>
        </tr>
    </table>
@endsection

