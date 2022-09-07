import { Head } from '@inertiajs/inertia-react'
import { useState } from 'react'
import Layout from '@/Layouts/Layout'
import { Player } from '@/interfaces/Player'
import PlayerShowStats from './components/PlayerShowStats'

export type PlayerShowProps = {
  player: Player
}

export default function PlayerShow({ player }: PlayerShowProps) {
  const [tab, setTab] = useState('overview')

  const handleChangeTab = (tab: string) => {
    setTab(tab)
  }

  return (
    <Layout>
      <Head title={player.know_as} />

      <section style={{ background: 'linear-gradient(90deg,#28395c,#111827)' }} className="relative">
        <div className="absolute overflow-hidden w-full bottom-0 left-0 top-0">
          <svg
            style={{ fill: '#28395c' }}
            className="hidden md:block absolute w-full overflow-hidden bottom-0"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1496 270">
            <path
              d="M1496 6.3c-194.1 93.7-427.6 160.3-645.2 183.2L993.8 46C884.6 66.3 806.4 69.9 691.4 69.9c-66.4 0-129.7-3.4-192-10.1s-123.6-16.9-186-30.3L342.7 0H0v67.3c68.3 26.5 138.4 49.4 208.5 67.5l-85.1 85.5C184.5 240 249.3 256.5 316 270h1180V6.3z"
              fillRule="evenodd"
              clipRule="evenodd"></path>
          </svg>
          <svg
            className="md:hidden"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 375 188"
            enableBackground="new 0 0 375 188">
            <filter filterUnits="objectBoundingBox" height="100%" id="a" width="100%" x="0%" y="0%">
              <feGaussianBlur in="SourceGraphic"></feGaussianBlur>
            </filter>
            <path
              d="M375 188V47.5c-16.3.9-33.2 1.2-52.8 1.2-53.2 0-101.4-5.4-151.4-16.3L202.9 0H0v28.4c41.1 18.8 85 34.9 128.8 46.3l-34.1 34.4c80.2 25.9 175.7 38.2 264.4 38.2L318.7 188H375z"
              fillRule="evenodd"
              clipRule="evenodd"
              filter="url(#a)"></path>
          </svg>
        </div>

        <div className="container mx-auto">
          <div className="flex relative pt-2">
            <div className="pl-16 pt-4 w-72 h-60 mt-auto">
              <img src={player.image_url.replace('22_60', '22_240')} alt={player.know_as} />
            </div>

            <div>
              <div className="pt-2 pb-28 pl-16">
                <div className="text-6xl">-</div>

                <h1 className="font-bold text-4xl">{player.know_as}</h1>
                <h2 className="font-bold text-xl">{player.full_name}</h2>
              </div>

              <div className="relative">
                <nav className="absolute -top-12">
                  <ul className="flex gap-2">
                    <li className="inline-block" onClick={() => handleChangeTab('overview')} aria-hidden="true">
                      <button
                        className={`rounded-t py-1 px-3 leading-10 ${
                          tab === 'overview' ? 'bg-gray-800' : 'bg-gray-500'
                        }`}>
                        Ovewview
                      </button>
                    </li>
                    <li className="inline-block rounded" onClick={() => handleChangeTab('stats')} aria-hidden="true">
                      <button
                        className={`rounded-t py-1 px-3 leading-10 ${tab === 'stats' ? 'bg-gray-800' : 'bg-gray-500'}`}>
                        Stats
                      </button>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div className="container mx-auto">
        <div className="flex">
          {/* SIDEBAR */}
          <nav className="w-72 pt-12 pr-10 pb-2 pl-2">sidebar</nav>

          <div className="w-full pl-7 relative">
            <div className="w-full">
              <div className="relative pl-10 pt-12 rounded-tl-md">
                <div className={`${tab === 'overview' ? 'block' : 'hidden'}`}>
                  <h3 className="text-2xl font-bold mb-2">Detalhes do Jogador</h3>

                  <div className="rounded flex justify-between border border-gray-200 p-2 mt-6">
                    <div className="flex justify-between py-3 px-2 w-1/3 border-r border-gray-200 items-center">
                      <span className="font-light text-xs">Nacionalidade</span>
                      <div className="font-bold">{player.nation?.name}</div>
                    </div>

                    <div className="flex justify-between py-3 px-2 w-1/3 border-r border-gray-200 items-center">
                      <span className="font-light text-xs">Idade</span>
                      <div className="font-bold">({player.age})</div>
                    </div>

                    <div className="flex justify-between py-3 px-2 w-1/3 items-center">
                      <span className="font-light text-xs">Altura</span>
                      <div className="font-bold">{player.length}cm</div>
                    </div>
                  </div>
                </div>

                <div className={`${tab === 'stats' ? 'block' : 'hidden'}`}>
                  <PlayerShowStats />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  )
}
