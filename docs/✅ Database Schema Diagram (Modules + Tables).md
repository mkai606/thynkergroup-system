flowchart TB
  subgraph Public_Website["🌐 Public Website Registration"]
    REG[registrations]
    REGSOC[registration_social_profiles]
    REGHL[registration_highlight_posts]
    REGEV[registration_evidence]
  end

  subgraph Core["🧠 Core Platform"]
    U[users]
    USOC[user_social_profiles]
    TIER[tier_rules]
    VIP[vip_memberships]
    REF[referrals]
    WAL[wallets]
    WTX[wallet_transactions]
    EXP[exp_events]
    LEAD[leaderboard_snapshots]
  end

  subgraph Campaigns["📣 Campaign / Task Engine"]
    CAM[campaigns]
    TASK[tasks]
    APP[task_applications]
    SUB[task_submissions]
    APR[task_approvals]
  end

  subgraph Ops["🛡️ Ops: Fraud + AI"]
    FR[fraud_events]
    RISK[risk_scores]
    AI[ai_assignments]
  end

  subgraph Comms["📡 Communications"]
    BC[broadcasts]
    NOTIF[notifications]
  end

  subgraph Settings["⚙️ System Settings"]
    SET[system_settings]
    SETLOG[system_settings_audit_log]
  end

  subgraph Pay["💰 Payment / Payout"]
    PAY[payout_requests]
    PAYTX[payout_transactions]
  end

  %% Website to Core
  REG --> REGSOC
  REG --> REGHL
  REG --> REGEV
  REG -->|approved -> create| U

  %% User profile links
  U --> USOC
  U --> WAL
  WAL --> WTX
  U --> VIP
  U --> REF
  U --> EXP
  EXP --> LEAD

  %% Campaign engine links
  CAM --> TASK
  U --> APP
  TASK --> APP
  APP --> SUB
  SUB --> APR

  %% Fraud & AI
  SUB --> FR
  U --> RISK
  TASK --> AI
  U --> AI

  %% Comms
  BC --> NOTIF
  U --> NOTIF

  %% Settings
  SET --> SETLOG

  %% Payouts
  U --> PAY
  APR -->|approved/paid| PAY
  PAY --> PAYTX
  PAYTX --> WTX