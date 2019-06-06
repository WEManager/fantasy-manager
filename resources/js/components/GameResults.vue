<template>
    <div>
        <h1>{{ playername }}</h1>
        <h1>{{ points }}</h1>

        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Points</th>
                <th>Left</th>
            </tr>
            </thead>
            <tbody>
            <input-game-result :gameid="gameid" :points="points"/>
            <tr v-for="event in events">
                <td>{{ event.value }}</td>
                <td>{{ event.points }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['gameid', 'player', 'playername'],
        mounted() {
            axios.get('/api/games/' + this.gameid).then(({data}) => {
                this.events = data[this.player + '_events'];

                this.events.map(event => {
                    this.points -= event.value;
                    event.points = this.points;
                })
            });
        },
        data() {
            return {
                points: 501,
                events: []
            }
        }
    }
</script>
