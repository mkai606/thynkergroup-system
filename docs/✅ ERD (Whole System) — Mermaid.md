erDiagram

  %% -----------------------
  %% Public Website Intake
  %% -----------------------
  REGISTRATIONS {
    string id PK
    string full_name
    string phone
    string email
    string referral_code_used
    string status "pending|approved|rejected"
    string verification_status "unverified|verified"
    datetime submitted_at
    string notes
  }

  REGISTRATION_SOCIAL_PROFILES {
    string id PK
    string registration_id FK
    string platform "instagram|tiktok|facebook|x|threads|youtube"
    string handle
    int followers
    string profile_url
  }

  REGISTRATION_HIGHLIGHT_POSTS {
    string id PK
    string registration_id FK
    string post_url
    string post_type "image|video"
    int likes
    int comments
  }

  REGISTRATION_EVIDENCE {
    string id PK
    string registration_id FK
    string evidence_type "screenshot|receipt|other"
    string file_url
  }

  %% -----------------------
  %% Core Users
  %% -----------------------
  USERS {
    string id PK
    string name
    string handle
    string phone
    string email
    string avatar_url
    string status "active|inactive"
    string tier "A|B|C|D|E"
    int follower_count
    float rating
    float success_rate
    string platform_primary
    string sidekick_level "premium|vip"
    string vip_status "none|requirements_met|payment_submitted|active"
    datetime join_date
    int total_exp
    int monthly_exp
    int rank_position
    boolean verified_badge
    boolean flagged
  }

  USER_SOCIAL_PROFILES {
    string id PK
    string user_id FK
    string platform
    string handle
    int followers
    string profile_url
    boolean is_primary
    datetime last_synced_at
  }

  TIER_RULES {
    string id PK
    int tierA_minFollowers
    int tierB_minFollowers
    int tierC_minFollowers
    int tierD_minFollowers
    int tierE_minFollowers
    boolean autoPromotion
    boolean autoDowngrade
    datetime updated_at
  }

  VIP_MEMBERSHIPS {
    string id PK
    string user_id FK
    string status "eligible|payment_submitted|active|expired|rejected"
    datetime approved_at
    datetime expires_at
    string receipt_url
    float fee_amount
    boolean auto_renewal
  }

  REFERRALS {
    string id PK
    string referrer_user_id FK
    string referred_user_id FK
    string referral_code
    datetime created_at
  }

  WALLETS {
    string id PK
    string user_id FK
    float balance
    string currency "MYR"
    datetime updated_at
  }

  WALLET_TRANSACTIONS {
    string id PK
    string wallet_id FK
    string type "credit|debit"
    string reason "campaign_payout|vip_fee|adjustment"
    float amount
    string reference_type
    string reference_id
    datetime created_at
  }

  EXP_EVENTS {
    string id PK
    string user_id FK
    string type "earn|bonus|decay|admin_adjust"
    int exp_amount
    string reference_type
    string reference_id
    datetime created_at
  }

  LEADERBOARD_SNAPSHOTS {
    string id PK
    string period "weekly|monthly|quarterly"
    date period_start
    date period_end
    datetime generated_at
  }

  LEADERBOARD_ENTRIES {
    string id PK
    string snapshot_id FK
    string user_id FK
    int exp
    int rank
    boolean vip
  }

  %% -----------------------
  %% Campaign / Task Engine
  %% -----------------------
  CAMPAIGNS {
    string id PK
    string title
    string brand
    string status "draft|active|paused|ended"
    float total_budget
    float spent_budget
    datetime start_at
    datetime end_at
    datetime created_at
  }

  TASKS {
    string id PK
    string campaign_id FK
    string title
    string platform
    string type
    string description
    string access_level "public|vip_only"
    int min_followers
    int exp_reward
    float reward_amount
    int slots_total
    int slots_taken
    date deadline
    boolean instructions_locked
    string hidden_details
    string status "open|closed"
  }

  TASK_INSTRUCTIONS {
    string id PK
    string task_id FK
    int step_no
    string instruction
  }

  TASK_HASHTAGS {
    string id PK
    string task_id FK
    string hashtag
  }

  TASK_APPLICATIONS {
    string id PK
    string task_id FK
    string user_id FK
    string status "applied|accepted|rejected|submitted|approved|paid"
    datetime applied_at
    datetime accepted_at
    datetime updated_at
  }

  TASK_SUBMISSIONS {
    string id PK
    string application_id FK
    string submission_type "link|screenshot|file"
    string proof_url
    datetime submitted_at
  }

  TASK_APPROVALS {
    string id PK
    string submission_id FK
    string status "pending|approved|rejected"
    boolean auto_verified
    string detected_handle
    string fraud_risk
    int exp_awarded
    datetime reviewed_at
    string reviewer_id
  }

  %% -----------------------
  %% Fraud / AI
  %% -----------------------
  FRAUD_EVENTS {
    string id PK
    string user_id FK
    string submission_id FK
    string type "duplicate_ip|fake_followers|handle_mismatch|other"
    int severity_score
    string details
    datetime created_at
  }

  RISK_SCORES {
    string id PK
    string user_id FK
    float risk_score
    int duplicate_ip_score
    int fake_follower_score
    int behavior_score
    datetime updated_at
  }

  AI_ASSIGNMENTS {
    string id PK
    string task_id FK
    string user_id FK
    int confidence
    float performance_weight
    float roi_weight
    float risk_weight
    string decision "suggest|auto_assign|skip"
    datetime created_at
  }

  %% -----------------------
  %% Comms
  %% -----------------------
  BROADCASTS {
    string id PK
    string sender_type "mentor|admin|hq|community"
    string sender
    string audience "all|premium|vip"
    string message
    datetime created_at
  }

  NOTIFICATIONS {
    string id PK
    string user_id FK
    string type "exp|payment|referral|task|system"
    string message
    boolean is_read
    datetime created_at
  }

  %% -----------------------
  %% Settings
  %% -----------------------
  SYSTEM_SETTINGS {
    string id PK
    json tierConfig
    json vipConfig
    json campaignConfig
    json rewardConfig
    json fraudConfig
    json paymentConfig
    json aiEngineConfig
    datetime updated_at
  }

  SYSTEM_SETTINGS_AUDIT_LOG {
    string id PK
    string settings_id FK
    string changed_by_user_id
    json diff
    datetime created_at
  }

  %% -----------------------
  %% Payout / Payment
  %% -----------------------
  PAYOUT_REQUESTS {
    string id PK
    string user_id FK
    string approval_id FK
    float amount
    string provider "TNG|DuitNow|Bank"
    string status "requested|processing|paid|failed"
    int payment_delay_days
    datetime created_at
  }

  PAYOUT_TRANSACTIONS {
    string id PK
    string payout_request_id FK
    string provider_ref
    float amount
    string status "success|failed"
    datetime processed_at
  }

  %% -----------------------
  %% Relationships
  %% -----------------------
  REGISTRATIONS ||--o{ REGISTRATION_SOCIAL_PROFILES : has
  REGISTRATIONS ||--o{ REGISTRATION_HIGHLIGHT_POSTS : has
  REGISTRATIONS ||--o{ REGISTRATION_EVIDENCE : has
  REGISTRATIONS }o--|| USERS : "approved_creates"

  USERS ||--o{ USER_SOCIAL_PROFILES : has
  USERS ||--|| WALLETS : owns
  WALLETS ||--o{ WALLET_TRANSACTIONS : logs
  USERS ||--o{ EXP_EVENTS : earns
  LEADERBOARD_SNAPSHOTS ||--o{ LEADERBOARD_ENTRIES : contains
  USERS ||--o{ LEADERBOARD_ENTRIES : ranked

  USERS ||--o{ VIP_MEMBERSHIPS : has
  USERS ||--o{ REFERRALS : refers
  USERS ||--o{ REFERRALS : referred

  CAMPAIGNS ||--o{ TASKS : contains
  TASKS ||--o{ TASK_INSTRUCTIONS : steps
  TASKS ||--o{ TASK_HASHTAGS : tags
  USERS ||--o{ TASK_APPLICATIONS : applies
  TASKS ||--o{ TASK_APPLICATIONS : receives
  TASK_APPLICATIONS ||--o{ TASK_SUBMISSIONS : submits
  TASK_SUBMISSIONS ||--o{ TASK_APPROVALS : reviewed

  TASK_SUBMISSIONS ||--o{ FRAUD_EVENTS : triggers
  USERS ||--o{ FRAUD_EVENTS : flagged
  USERS ||--|| RISK_SCORES : has
  TASKS ||--o{ AI_ASSIGNMENTS : suggested_for
  USERS ||--o{ AI_ASSIGNMENTS : suggested_to

  BROADCASTS ||--o{ NOTIFICATIONS : fanout
  USERS ||--o{ NOTIFICATIONS : receives

  SYSTEM_SETTINGS ||--o{ SYSTEM_SETTINGS_AUDIT_LOG : audits

  TASK_APPROVALS ||--o{ PAYOUT_REQUESTS : generates
  USERS ||--o{ PAYOUT_REQUESTS : requests
  PAYOUT_REQUESTS ||--|| PAYOUT_TRANSACTIONS : processed_as
  PAYOUT_TRANSACTIONS }o--|| WALLET_TRANSACTIONS : "wallet_credit_reference"