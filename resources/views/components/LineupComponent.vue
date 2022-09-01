<template>
    <div class="col-xs-12">
        <form @submit.prevent="submit">
            <div class="row">
                <label for="goalkeeper">Goalkeeper</label>
                <select v-if="JSON.parse(players).length" v-model="player_1" name="player_1" id="goalkeeper">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_1 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>
            <div class="row">
                <select v-model="position_2" name="position_2" id="position_2">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_2 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_2" name="player_2" id="player_2">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_2 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>
            <div class="row">
                <select v-model="position_3" name="position_3" id="position_3">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_3 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_3" name="player_3" id="player_3">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_3 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_4" name="position_4" id="position_4">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_4 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_4" name="player_4" id="player_4">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_4 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_5" name="position_5" id="position_5">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_5 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_5" name="player_5" id="player_5">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_5 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_6" name="position_6" id="position_6">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_6 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_6" name="player_6" id="player_6">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_6 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_7" name="position_7" id="position_7">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_7 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_7" name="player_7" id="player_7">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_7 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_8" name="position_8" id="position_8">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_8 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_8" name="player_8" id="player_8">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_8 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_9" name="position_9" id="position_9">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_9 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_9" name="player_9" id="player_9">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_9 || !selectedPlayers.includes(player.id)">{{ player.firstname }}
                        {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_10" name="position_10" id="position_10">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_10 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_10" name="player_10" id="player_10">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_10 || !selectedPlayers.includes(player.id)">{{ player.firstname
                        }} {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <div class="row">
                <select v-model="position_11" name="position_11" id="position_11">
                    <option
                            :value="position"
                            v-for="(position, index) in JSON.parse(positions)"
                            v-if="position === position_11 || !selectedPositions.includes(position)">{{ position }}
                    </option>
                </select>
                <select v-model="player_11" name="player_11" id="player_11">
                    <option v-for="(player, index) in JSON.parse(players)"
                            :value="player.id"
                            v-if="player.id === player_11 || !selectedPlayers.includes(player.id)">{{ player.firstname
                        }} {{
                        player.lastname }}
                    </option>
                </select>
            </div>

            <input type="submit" value="Save">
        </form>
    </div>
</template>

<script>
    export default {
        props: [
            'players',
            'positions',
            'token',
            'action',
            'lineup',
        ],

        mounted() {
            this.setPlayersFromLineup();
        },

        data() {
            return {
                selectedPlayers: [],
                selectedPositions: [],
                position_1: 'GK',
                position_2: null,
                position_3: null,
                position_4: null,
                position_5: null,
                position_6: null,
                position_7: null,
                position_8: null,
                position_9: null,
                position_10: null,
                position_11: null,
                player_1: null,
                player_2: null,
                player_3: null,
                player_4: null,
                player_5: null,
                player_6: null,
                player_7: null,
                player_8: null,
                player_9: null,
                player_10: null,
                player_11: null,
            }
        },

        methods: {

            setPlayersFromLineup() {

                if (this.lineup) {
                    let lineup = JSON.parse(this.lineup);
                    this.position_1 = lineup.position_1;
                    this.position_2 = lineup.position_2;
                    this.position_3 = lineup.position_3;
                    this.position_4 = lineup.position_4;
                    this.position_5 = lineup.position_5;
                    this.position_6 = lineup.position_6;
                    this.position_7 = lineup.position_7;
                    this.position_8 = lineup.position_8;
                    this.position_9 = lineup.position_9;
                    this.position_10 = lineup.position_10;
                    this.position_11 = lineup.position_11;

                    this.player_1 = lineup.player_1;
                    this.player_2 = lineup.player_2;
                    this.player_3 = lineup.player_3;
                    this.player_4 = lineup.player_4;
                    this.player_5 = lineup.player_5;
                    this.player_6 = lineup.player_6;
                    this.player_7 = lineup.player_7;
                    this.player_8 = lineup.player_8;
                    this.player_9 = lineup.player_9;
                    this.player_10 = lineup.player_10;
                    this.player_11 = lineup.player_11;
                }
            },

            submit() {
                axios.post(this.action, {
                    position_1: this.position_1,
                    position_2: this.position_2,
                    position_3: this.position_3,
                    position_4: this.position_4,
                    position_5: this.position_5,
                    position_6: this.position_6,
                    position_7: this.position_7,
                    position_8: this.position_8,
                    position_9: this.position_9,
                    position_10: this.position_10,
                    position_11: this.position_11,
                    player_1: this.player_1,
                    player_2: this.player_2,
                    player_3: this.player_3,
                    player_4: this.player_4,
                    player_5: this.player_5,
                    player_6: this.player_6,
                    player_7: this.player_7,
                    player_8: this.player_8,
                    player_9: this.player_9,
                    player_10: this.player_10,
                    player_11: this.player_11,
                })
            }
        },

        watch: {
            position_2: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_3: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_4: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_5: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_6: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_7: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_8: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_9: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_10: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },
            position_11: function (val, old) {
                this.selectedPositions = updateArray(val, old, this.selectedPositions);
            },

            player_1: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_2: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_3: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_4: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_5: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_6: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_7: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_8: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_9: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_10: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
            player_11: function (val, old) {
                this.selectedPlayers = updateArray(val, old, this.selectedPlayers);
            },
        }

    }

    function updateArray(newValue, oldValue, arrayToUpdate) {
        if (oldValue !== null) {
            let deleteIndex = -1;
            arrayToUpdate.map((item, index) => {
                if (item === oldValue) deleteIndex = index;
            });
            arrayToUpdate.splice(deleteIndex, 1);
        }
        arrayToUpdate.push(newValue);

        return arrayToUpdate;
    }
</script>
