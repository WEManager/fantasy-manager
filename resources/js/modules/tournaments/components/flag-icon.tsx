interface FlagIconProps {
  nationality: string
}

export function FlagIcon({ nationality }: FlagIconProps) {
  return (
    <span
      className={`flag-icon flag-icon-${nationality}`}
      style={{
        width: '1.2em',
        height: '1.2em',
        display: 'inline-block',
        backgroundSize: 'contain',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
      }}
    />
  )
}
