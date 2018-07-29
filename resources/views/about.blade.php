@extends('layout')

@section('content')
<H1> Team info and Documents go here</H1>
<p>The request starts here:</p>
{{ $request }}
<p>The request ends here</p>
<session_table v-bind:table_title='upcoming'></session_table>
<session_table :table_title='processed'></session_table>


@endsection
