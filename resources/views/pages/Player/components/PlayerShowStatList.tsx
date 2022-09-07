import _ from 'lodash'
import type { Goalkeeping, Mental, Physical, Technical } from '@/interfaces/Player'
import { useLang } from '@/Hooks/Lang'

type PlayerShowStatListProps = {
  title: string
  stats: Technical | Mental | Goalkeeping | Physical
}

export default function PlayerShowStatList({ title, stats }: PlayerShowStatListProps) {
  const { attribute } = useLang()

  return (
    <div>
      <h3 className="text-xl font-bold mb-3">{title}</h3>

      <ul>
        {Object.entries<number>(stats).map(([atribute, value]) => (
          <li key={atribute} className="flex justify-between">
            <span>{attribute(_.startCase(atribute))}</span>

            <span>{value}</span>
          </li>
        ))}
      </ul>
    </div>
  )
}
