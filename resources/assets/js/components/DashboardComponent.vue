<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link :to="{name: 'create-surf'}" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>

        <matches-component
                :matches="matches.matches"
                :forecasts="matches.refs.forecasts"
                :surfs="matches.refs.surfs"
                :spots="matches.refs.spots"
        ></matches-component>
    </div>
</template>

<script>
    Vue.component('matches-component', require('./MatchesComponent'));

    export default {
        props: ['flash_message'],
        data() {
            return {
                matches: {
                    matches: {},
                    refs: {}
                },
            }
        },
        created() {
            axios.get("/api/v1/match")
                .then(response => {
                    this.matches = response.data;
                });
        }
    }
</script>