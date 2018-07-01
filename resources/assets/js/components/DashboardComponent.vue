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

            <tbody v-for="(spots, forecast_id) in this.matches.matches">
                <tr>
                    <th colspan="8">{{ getForecast(forecast_id).threeHourTimeText }}</th>
                </tr>

                <tr class="match-summary table-info" v-for="(surfs, spot_id) in spots">
                    <td nowrap="nowrap"><strong>{{ getSpot(spot_id).name }}</strong></td>
                    <td>{{ getForecast(forecast_id).swell_minBreakingHeight }}-{{ getForecast(forecast_id).swell_maxBreakingHeight }}ft</td>
                    <td nowrap="nowrap">
                        <img src="http://cdnimages.magicseaweed.com/star_filled.png" v-for="star in new Array(getForecast(forecast_id).solidRating)" />
                        <img src="http://cdnimages.magicseaweed.com/star_empty.png" v-for="star in new Array(getForecast(forecast_id).fadedRating)" />
                    </td>
                    <td nowrap="nowrap" class="text-center">
                        <span :class="getForecastSwellDirection(forecast_id)"></span><br />
                        {{ getForecast(forecast_id).swell_primary_height }}ft @ {{ getForecast(forecast_id).swell_primary_period }}s
                    </td>
                    <td nowrap="nowrap" class="text-center">
                        <span :class="getForecastWindDirection(forecast_id)"></span><br />
                        {{ getForecast(forecast_id).wind_speed }} mph
                    </td>
                    <td>{{ surfs.averages.swell_size }}ft</td>
                    <td>{{ windSpeed(surfs.averages.wind_speed) }}</td>
                    <td>{{ Math.round(surfs.averages.average_match, 0) }}%</td>
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