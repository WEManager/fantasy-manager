import { Head } from '@inertiajs/inertia-react'
import Layout from '@/Layouts/Layout'
import { Game } from '@/interfaces/Game'

type GameShowProps = {
  game: Game
}

export default function GameShow({ game }: GameShowProps) {
  console.log('🚀 ~ file: Show.tsx ~ line 10 ~ GameShow ~ game', game)
  function HeaderClub({ away = false }: { away?: boolean }) {
    const club = away ? game.away_team : game.home_team
    const score = away ? game.awayteam_score : game.hometeam_score

    return (
      <div className={`flex items-center ${away && 'flex-row-reverse'} gap-4`}>
        <h2 className="font-bold text-xl">{club.name}</h2>

        <span>{score}</span>
      </div>
    )
  }

  function ClubeLineup({ away = false }: { away?: boolean }) {
    const lineup = away ? game.away_lineup : game.home_lineup
    console.log('🚀 ~ file: Show.tsx ~ line 25 ~ ClubeLineup ~ lineup', lineup)

    return (
      <div className="flex flex-col">
        {Array(11)
          .fill(1)
          .map((_, key) => (
            <div key={key} className={`flex items-baseline ${away && 'flex-row-reverse'} gap-2`}>
              <span className="text-slate-400 text-sm">{lineup?.[`position_${key + 1}`]}</span>
              <p>{lineup?.[`player_${key + 1}`].know_as}</p>
            </div>
          ))}
      </div>
    )
  }

  return (
    <Layout>
      <Head title="Game" />

      <div className="container mx-auto">
        <div className="flex flex-col items-center mt-2">
          <h1 className="font-bold text-2xl">{game?.group.name}</h1>

          <div className="flex justify-center gap-5 mt-6">
            <HeaderClub />

            <HeaderClub away />
          </div>

          <span className="text-slate-400 mt-2">{game.game_status}</span>
        </div>

        <div className="flex justify-between">
          <ClubeLineup />

          <div className="flex flex-col">
            {game.messages.map(({ minute, message }) => (
              <div key={`${minute}_${message}`} className="flex items-center gap-2">
                <span>{minute}&apos;</span> <p key={message}>{message}</p>
              </div>
            ))}
          </div>

          <ClubeLineup away />
        </div>
      </div>
    </Layout>
  )
}
