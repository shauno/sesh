<template>
    <div>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">
                    <h3>
                        Closest Matches

                        <div class="dropdown float-right">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dateSelectButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ selectedDay.display }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dateSelectButton">
                                <a
                                        :class="{'dropdown-item': true}"
                                        href="#"
                                        v-for="day in matchDays"
                                        v-on:click="selectedDay = day"
                                >
                                    {{ day.display }}
                                </a>
                            </div>
                        </div>
                    </h3>
                </th>
            </tr>
            </thead>
        </table>

        <div v-if="this.matches">
            <table class="table table-hover" v-for="(matches, timestamp) in this.matches">
            <matches-spot-averages
                    v-for="(match, spot_id) in matches"
                    v-bind:timestamp="timestamp"
                    v-bind:match="match"
                    v-bind:spot_id="spot_id"
                    v-bind:key="spot_id"
                    v-bind:spots="spots"
                    v-bind:forecasts="forecasts"
                    v-bind:surfs="surfs"
            ></matches-spot-averages>
            </table>
        </div>
        <div v-else class="alert alert-dark" role="alert">
            No matches found
        </div>
    </div>
</template>

<script>
    Vue.component('matches-spot-averages', require('./MatchesSpotAveragesComponent'));

    export default {
        data() {
            return {
                selectedDay: { //Yes, this is poo, I'm still figuring out this VueJS thing
                    key: (new Date()).toISOString().substr(0, 10),
                    display: (new Date()).toLocaleDateString('en-gb-u-ca-iso8601', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric'
                    })
                },
                matches: {},
                forecasts: {},
                spots: {},
                surfs: {}
            }
        },
        watch: {
            selectedDay: function(newDay, oldDay) {
                this.findMatches(newDay.key);
            }
        },
        computed: {
            matchDays: function() {
                let today = new Date();
                let days = [];
                for(let i = 0; i<5; i++) {
                    if(i) {
                        today.setTime(today.getTime() + 86400000); //add 1 day in milliseconds to day
                    }
                    days[i] = {
                        key: today.toISOString().substr(0, 10),
                        display: today.toLocaleDateString('en-gb-u-ca-iso8601', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'numeric',
                            day: 'numeric'
                        })
                    };
                }
                return days;
            }
        },
        methods: {
            findMatches: function(date) {
                this.matches = [];
                axios.get("/api/v1/match?date="+date)
                    .then(response => {
                        this.matches = response.data.matches;
                        this.forecasts = response.data.refs.forecasts;
                        this.spots = response.data.refs.spots;
                        this.surfs = response.data.refs.surfs;
                    });

            }
        },
        created() {
            this.findMatches(this.selectedDay.key);
        }
    }
</script>
