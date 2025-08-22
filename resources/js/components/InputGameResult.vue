<template>
    <tr>
        <!--<td><input max="3" type="number" value="3" style="width: 50px"></td>-->
        <td><input type="number" style="width: 100px" @change="pointsLeft" @keyup.enter="submitScore" v-model="roundPoints"></td>
        <td><input type="number" disabled style="width: 50px" v-model="pointsLeft"></td>
    </tr>
</template>

<script>
import axios from 'axios'

export default {
  props: ['gameid', 'points'],
  data() {
    return {
      roundPoints: 0,
    }
  },
  methods: {
    submitScore() {
      axios.post(`/api/games/${this.gameid}/points`).then((data) => {
        console.log(data)
      })
    },
  },
  computed: {
    pointsLeft() {
      return this.points - this.roundPoints
    },
  },
}
</script>