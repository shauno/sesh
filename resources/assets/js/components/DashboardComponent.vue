<template>
    <div>
        <div class="alert alert-primary" v-show="flash_message">
            {{ flash_message }}
        </div>

        <p><router-link :to="{name: 'create-surf'}" class="btn btn-primary btn-lg">Log a Surf &raquo;</router-link></p>

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

            <tbody v-for="(spots, timestamp) in this.matches.matches">
                <tr>
                    <th colspan="8">{{ (new Date(timestamp*1000)).getHours()+1 }}:00</th>
                </tr>

                <tr class="match-summary table-info" v-for="(match, spot_id) in spots">
                    <td nowrap="nowrap"><strong>{{ getSpot(spot_id).name }}</strong></td>
                    <td>{{ getForecast(match.forecast_id).swell_minBreakingHeight }}-{{ getForecast(match.forecast_id).swell_maxBreakingHeight }}ft</td>
                    <td nowrap="nowrap">
                        <img src="http://cdnimages.magicseaweed.com/star_filled.png" v-for="star in new Array(getForecast(match.forecast_id).solidRating)" />
                        <img src="http://cdnimages.magicseaweed.com/star_empty.png" v-for="star in new Array(getForecast(match.forecast_id).fadedRating)" />
                    </td>
                    <td nowrap="nowrap" class="text-center">
                        <span :class="getForecastSwellDirection(match.forecast_id)"></span><br />
                        {{ getForecast(match.forecast_id).swell_primary_height }}ft @ {{ getForecast(match.forecast_id).swell_primary_period }}s
                    </td>
                    <td nowrap="nowrap" class="text-center">
                        <span :class="getForecastWindDirection(match.forecast_id)"></span><br />
                        {{ getForecast(match.forecast_id).wind_speed }} mph
                    </td>
                    <td>{{ match.averages.swell_size }}ft</td>
                    <td>{{ windSpeed(match.averages.wind_speed) }}</td>
                    <td>{{ Math.round(match.averages.average_match, 0) }}%</td>
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
        methods: {
            getForecast(forecast_id) {
                return this.matches.refs.forecasts[forecast_id];
            },
            getSpot(spot_id) {
                return this.matches.refs.spots[spot_id];
            },
            getForecastSwellDirection(forecast_id) {
                let classes = {'msw-swa': true};
                classes['msw-swa-' + Math.round(this.getForecast(forecast_id).swell_primary_direction / 5) * 5] = true;
                return classes;
            },
            getForecastWindDirection(forecast_id) {
                let classes = {'msw-ssa': true};
                classes['msw-ssa-' + Math.round(this.getForecast(forecast_id).wind_direction / 5) * 5] = true;
                return classes;
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