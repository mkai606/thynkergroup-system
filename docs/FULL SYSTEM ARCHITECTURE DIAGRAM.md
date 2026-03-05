┌────────────────────────────────────────────────────────────┐
│                    🌐 PUBLIC LAYER                         │
│                                                            │
│  ┌──────────────────────────────┐                          │
│  │   🌍 Registration Website    │                          │
│  │   (Landing + Signup Form)    │                          │
│  │                              │                          │
│  │ - Creator registration       │                          │
│  │ - Social profile submit      │                          │
│  │ - Referral code              │                          │
│  │ - Campaign interest          │                          │
│  └──────────────┬───────────────┘                          │
│                 │                                          │
└─────────────────▼──────────────────────────────────────────┘


┌────────────────────────────────────────────────────────────┐
│                    ⚙️ APPLICATION LAYER                     │
│                                                            │
│ ┌──────────────────────┐   ┌────────────────────────┐     │
│ │ 🧑‍💼 Admin HQ Dashboard │   │ 📱 Agent / Creator App │     │
│ │                      │   │                        │     │
│ │ - Campaign control   │   │ - View tasks           │     │
│ │ - Agent management   │   │ - Submit proof         │     │
│ │ - Fraud monitoring   │   │ - Earn rewards         │     │
│ │ - Payment control    │   │ - VIP upgrade          │     │
│ │ - Analytics          │   │ - Wallet               │     │
│ │ - AI assignment      │   │ - Leaderboard          │     │
│ └──────────┬───────────┘   └───────────┬───────────┘     │
│            │                           │                  │
└────────────▼───────────────────────────▼──────────────────┘


┌────────────────────────────────────────────────────────────┐
│                     🧠 CORE SYSTEM LAYER                    │
│                                                            │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                 Backend API Server                  │   │
│  │                                                     │   │
│  │ - Authentication                                   │   │
│  │ - Task engine                                      │   │
│  │ - Tier engine                                      │   │
│  │ - Reward engine                                    │   │
│  │ - VIP engine                                       │   │
│  │ - Fraud engine                                     │   │
│  │ - AI assignment engine                             │   │
│  └──────────────┬──────────────────────────────────────┘   │
│                 │                                          │
│   ┌─────────────▼──────────────┐   ┌───────────────────┐   │
│   │        Database            │   │ Payment Gateway   │   │
│   │                            │   │                   │   │
│   │ - users                    │   │ TNG / DuitNow     │   │
│   │ - campaigns                │   │ bank transfer     │   │
│   │ - tasks                    │   │                   │   │
│   │ - submissions              │   └───────────────────┘   │
│   │ - rewards                  │                           │
│   │ - fraud logs               │                           │
│   └────────────────────────────┘                           │
└────────────────────────────────────────────────────────────┘

🧑 USER JOURNEY FLOW
1. Creator visits website
2. Registers account
3. Admin verifies
4. Creator gets access to Agent App
5. Creator performs campaign tasks
6. System verifies + rewards
7. Payment issued

🧠 SYSTEM DATA FLOW
Website → Backend → Database
                 ↓
           Admin Dashboard
                 ↓
           Agent App
                 ↓
           Payment System

 Frontend (Public Website)    
 Landing Page
  ↓
Creator Signup Form
  ↓
Social Account Verification
  ↓
Submission Queue      

✍️ Registration Form Fields
Basic info
name
phone
email
location

Social verification
Instagram / TikTok handle
follower count
highlight post
portfolio

System data
referral code
campaign interest
evidence screenshot

Matches your uploaded registration data.

🧠 Registration Processing Pipeline
User submits form
        ↓
Backend validation
        ↓
Fraud check
        ↓
Admin approval queue
        ↓
Agent account created
        ↓
Agent app access granted

🧑‍💼 ADMIN APPROVAL FLOW
Website signup
    ↓
Admin sees pending registration
    ↓
Check:
  - follower authenticity
  - engagement
  - social proof
    ↓
Approve / Reject
Already exists in your system as registration queue.

📱 AGENT APP ACCESS FLOW
Approved user
   ↓
Login
   ↓
System assigns tier
   ↓
Shows available campaigns

💰 PAYMENT FLOW
Task completed
    ↓
Proof submission
    ↓
Verification
    ↓
Reward issued
    ↓
QR payout / wallet