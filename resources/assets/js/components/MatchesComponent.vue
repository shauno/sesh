<template>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" colspan="8">
                <h3>
                    Closest Matches For Today
                </h3>
            </th>
        </tr>
        </thead>

        <tbody v-for="(matches, timestamp) in this.matches">
            <tr>
                <th colspan="8">{{ (new Date(timestamp*1000)).getHours()+1 }}:00</th>
            </tr>

            <matches-spot-averages
                    v-for="(match, spot_id) in matches"
                    v-bind:match="match"
                    v-bind:spot_id="spot_id"
                    v-bind:key="spot_id"
                    v-bind:spots="spots"
                    v-bind:forecasts="forecasts"
            ></matches-spot-averages>

        </tbody>

    </table>
</template>

<script>
    Vue.component('matches-spot-averages', require('./MatchesSpotAveragesComponent'));

    export default {
        props: [
            'matches',
            'forecasts',
            'spots',
            'surfs',
        ],
    }
</script>
