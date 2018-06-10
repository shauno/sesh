<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link :to="{name: 'create-surf'}" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>

        <table class="table table-hover" v-show="this.matches.length > 0">
            <thead>
            <tr>
                <th scope="col" colspan="3">
                    <h3>Predictions for today</h3>
                </th>
            </tr>
            <tr>
                <th scope="col">Time</th>
                <th scope="col">Spot</th>
                <th scope="col">Size</th>
                <th scope="col">Wind</th>
                <th scope="col">Match</th>
            </tr>
            </thead>
            <tbody v-for="match in matches">
                <tr v-for="surf in match.surfs">
                    <th scope="row">{{ match.msw_forecast.threeHourTimeText }}</th>
                    <td>{{ surf.surf.spot.name }}</td>
                    <td>{{ surf.surf.swell_size }}ft</td>
                    <td>{{ surf.surf.wind_speed }} {{ surf.surf.wind_direction }}</td>
                    <td>
                        <small>
                            Height: {{ Math.round(surf.matches.swell_height) }}%<br />
                            Period: {{ Math.round(surf.matches.swell_period) }}%<br />
                            Wind Speed: {{ Math.round(surf.matches.wind_speed) }}%<br />
                        </small>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>

<script>
    export default {
        props: ['flash_message'],
        data() {
            return {
                matches: [],
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