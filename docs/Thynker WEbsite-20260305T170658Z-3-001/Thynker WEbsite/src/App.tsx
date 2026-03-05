import { useState, useEffect, useRef } from 'react';
import { motion, useInView } from 'framer-motion';
import {
  Sparkles, Users, Star, TrendingUp, MessageSquare, Eye,
  ArrowRight, ChevronDown, Zap, Globe, Shield,
  Instagram, Twitter, Youtube, Menu, X, CheckCircle2, Layers
} from 'lucide-react';

const NAV_LINKS = [
  { label: 'Sidekick Marketing System', href: '#services' },
  { label: 'How It Works', href: '#how-it-works' },
  { label: 'Results', href: '#results' },
  { label: 'Pricing', href: '#pricing' },
  { label: 'Join Sidekick', href: '#join' },
];

const SERVICES = [
  {
    icon: <Eye size={28} />,
    title: 'Brand Awareness',
    tag: 'Visibility',
    tagClass: 'neon-tag',
    color: '#AAFF00',
    glow: 'glow-lime',
    desc: 'Sidekicks have tight-knit communities that trust their opinions. They create organic word-of-mouth exposure that influences purchasing decisions.',
    features: ['Trusted communities', 'Organic word-of-mouth', 'Purchase influence'],
  },
  {
    icon: <Star size={28} />,
    title: 'Product Trials & Reviews',
    tag: 'Credibility',
    tagClass: 'neon-tag-cyan',
    color: '#00BFFF',
    glow: 'glow-cyan',
    desc: 'Sidekicks test products and share authentic experiences.',
    features: ['Build credibility', 'Generate authentic reviews', 'Increase customer confidence'],
  },
  {
    icon: <MessageSquare size={28} />,
    title: 'Engagement & Interaction',
    tag: 'Connection',
    tagClass: 'neon-tag-green',
    color: '#7ec800',
    glow: 'glow-green',
    desc: 'Sidekicks maintain higher engagement rates compared to large influencers.',
    features: ['Reply to comments', 'Start conversations', 'Build trust with audiences'],
  },
];

const STATS = [
  { value: '1000+', label: 'Active Sidekicks', icon: <Users size={20} />, color: '#AAFF00' },
  { value: '50+', label: 'Brands Supported', icon: <Globe size={20} />, color: '#00BFFF' },
  { value: '5x', label: 'Average Campaign Reach', icon: <TrendingUp size={20} />, color: '#AAFF00' },
  { value: '300+', label: 'Affiliates Managed', icon: <Layers size={20} />, color: '#00BFFF' },
];

const HOW_IT_WORKS = [
  {
    step: '01',
    title: 'Strategy Session',
    desc: 'We analyze your brand positioning, audience and campaign goals.',
    color: '#AAFF00',
  },
  {
    step: '02',
    title: 'Sidekick Matching',
    desc: 'We match your brand with the right creators from our 1000+ Sidekick network.',
    color: '#00BFFF',
  },
  {
    step: '03',
    title: 'Campaign Launch',
    desc: 'Sidekicks create real conversations, product threads and content. This triggers platform algorithms.',
    color: '#AAFF00',
  },
  {
    step: '04',
    title: 'Track & Scale',
    desc: 'We monitor performance, engagement and conversions. Brands receive campaign insights and growth strategy.',
    color: '#00BFFF',
  },
];

const PLATFORMS = [
  { name: 'Instagram', icon: <Instagram size={20} />, color: '#AAFF00' },
  { name: 'TikTok', icon: <Zap size={20} />, color: '#00BFFF' },
  { name: 'Twitter/X', icon: <Twitter size={20} />, color: '#AAFF00' },
  { name: 'YouTube', icon: <Youtube size={20} />, color: '#00BFFF' },
  { name: 'Threads', icon: <Layers size={20} />, color: '#AAFF00' },
  { name: 'Facebook', icon: <Globe size={20} />, color: '#00BFFF' },
];

const SIDEKICK_PRICING = [
  { name: 'Starter', sidekicks: 50, price: 'RM3000', color: '#7ec800', featured: false },
  { name: 'Growth', sidekicks: 150, price: 'RM7000', color: '#AAFF00', featured: true },
  { name: 'Elite', sidekicks: 500, price: 'RM26000', color: '#00BFFF', featured: false },
  { name: 'Pro', sidekicks: 1000, price: 'RM39000', color: '#AAFF00', featured: false },
];

const AFFILIATE_PACKAGES = [
  { name: 'Package A', affiliates: 50, videos: 2, price: 'RM1500', color: '#AAFF00' },
  { name: 'Package B', affiliates: 100, videos: 2, price: 'RM2500', color: '#00BFFF' },
  { name: 'Package C', affiliates: 100, videos: 2, price: 'RM3000', note: 'Collaboration', color: '#AAFF00' },
];

const PREMIUM_PACKAGES = [
  { name: 'Package D', affiliates: 300, price: 'RM6000', color: '#00BFFF' },
  { name: 'Package E', affiliates: 500, price: 'RM9000', color: '#AAFF00' },
];

function AnimatedSection({ children, className = '', delay = 0 }: { children: React.ReactNode; className?: string; delay?: number }) {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: '-80px' });
  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, y: 50 }}
      animate={isInView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.7, delay, ease: [0.25, 0.46, 0.45, 0.94] }}
      className={className}
    >
      {children}
    </motion.div>
  );
}

export default function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const joinRef = useRef<HTMLElement | null>(null);
  const joinInView = useInView(joinRef, { margin: '0px 0px -40% 0px' });
  const hideSticky = joinInView;

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  return (
    <div className="min-h-screen relative" style={{ backgroundColor: '#080e00', color: '#f5ffdc' }}>

      {/* ===== NAV ===== */}
      <nav className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${scrolled ? 'nav-blur' : ''}`}>
        <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
          <a href="#" className="flex items-center gap-2">
            <div
              className="w-8 h-8 rounded-lg flex items-center justify-center"
              style={{ background: '#AAFF00', boxShadow: '0 0 16px rgba(170,255,0,0.6)' }}
            >
              <Sparkles size={16} style={{ color: '#080e00' }} />
            </div>
            <span className="font-display font-extrabold text-xl tracking-tight">
              Thynker
            </span>
          </a>

          <div className="hidden md:flex items-center gap-8">
            {NAV_LINKS.map(link => (
              <a
                key={link.label}
                href={link.href}
                className="text-sm font-medium transition-colors duration-200"
                style={{ color: '#7a9060' }}
                onMouseEnter={e => (e.currentTarget.style.color = '#AAFF00')}
                onMouseLeave={e => (e.currentTarget.style.color = '#7a9060')}
              >
                {link.label}
              </a>
            ))}
          </div>

          <div className="hidden md:flex items-center gap-3">
            <a
              href="#join"
              className="shimmer-btn px-5 py-2 rounded-full text-sm font-bold transition-all hover:scale-105"
              style={{ color: '#080e00' }}
            >
              Get Started
            </a>
          </div>

          <button className="md:hidden" onClick={() => setMenuOpen(!menuOpen)} style={{ color: '#AAFF00' }}>
            {menuOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>

        {menuOpen && (
          <motion.div
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            className="md:hidden nav-blur px-6 pb-6 flex flex-col gap-4"
          >
            {NAV_LINKS.map(link => (
              <a key={link.label} href={link.href} className="text-sm font-medium" style={{ color: '#7a9060' }} onClick={() => setMenuOpen(false)}>
                {link.label}
              </a>
            ))}
            <a href="#join" className="shimmer-btn px-5 py-2 rounded-full text-sm font-bold text-center" style={{ color: '#080e00' }}>
              Get Started
            </a>
          </motion.div>
        )}
      </nav>

      {/* ===== HERO ===== */}
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        {/* Background image */}
        <div
          className="absolute inset-0 z-0"
          style={{
            backgroundImage: 'url(/hero-bg.png)',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            opacity: 0.18,
          }}
        />
        {/* Dark green gradient overlay */}
        <div
          className="absolute inset-0 z-0"
          style={{
            background: 'radial-gradient(ellipse at 50% 0%, rgba(40,80,0,0.6) 0%, rgba(8,14,0,0.95) 70%)',
          }}
        />

        {/* Orbs */}
        <div className="orb-lime" style={{ width: 700, height: 700, top: '-5%', left: '-10%' }} />
        <div className="orb-cyan" style={{ width: 500, height: 500, top: '30%', right: '-8%' }} />
        <div className="orb-lime" style={{ width: 400, height: 400, bottom: '5%', right: '20%', opacity: 0.5 }} />

        <div className="relative z-10 max-w-6xl mx-auto px-6 text-center pt-28">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            <span className="neon-tag mb-6 inline-block">Sidekick Marketing by Thynker</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 40 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.15 }}
            className="font-display font-extrabold leading-none tracking-tight mt-4 mb-6"
            style={{ fontSize: 'clamp(48px, 8vw, 108px)' }}
          >
            <span className="gradient-text-full">Engineer Social Momentum</span><br />
            <span style={{ color: '#f5ffdc' }}>With 1000+ </span>
            <span className="text-glow-lime" style={{ color: '#AAFF00' }}>Sidekicks</span>
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.3 }}
            className="text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed"
            style={{ color: '#7a9060' }}
          >
            Community-driven brand amplification powered by trusted micro-creators and algorithm-triggered engagement.
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.45 }}
            className="flex flex-col sm:flex-row gap-4 justify-center items-center"
          >
            <a
              href="#join"
              className="shimmer-btn font-bold px-8 py-4 rounded-full flex items-center gap-2 text-base hover:scale-105 transition-transform"
              style={{ color: '#080e00' }}
            >
              Launch My Campaign <ArrowRight size={18} />
            </a>
            <a
              href="#how-it-works"
              className="glass-card font-semibold px-8 py-4 rounded-full flex items-center gap-2 text-base hover:scale-105 transition-transform"
              style={{ color: '#AAFF00', borderColor: 'rgba(170,255,0,0.2)' }}
            >
              See How It Works <ChevronDown size={18} />
            </a>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.6 }}
            className="mt-8 flex flex-col items-center gap-3"
          >
            <div className="text-xs md:text-sm uppercase tracking-widest" style={{ color: '#7a9060' }}>
              Trusted by 50+ Malaysian brands to scale awareness and engagement.
            </div>
            <div className="flex flex-wrap justify-center gap-3 opacity-90">
              {['WAWA', 'KAYMAN', 'MINAZ', 'MUKA', 'LUXE'].map(name => (
                <div
                  key={name}
                  className="glass-card px-4 py-2 rounded-full text-xs md:text-sm font-semibold tracking-wide"
                  style={{ color: '#7a9060', borderColor: 'rgba(170,255,0,0.15)' }}
                >
                  {name}
                </div>
              ))}
            </div>
          </motion.div>

          {/* Platform badges */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 1, delay: 0.7 }}
            className="flex flex-wrap justify-center gap-3 mt-16"
          >
            {PLATFORMS.map((p, i) => (
              <motion.div
                key={p.name}
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ delay: 0.7 + i * 0.08 }}
                className="glass-card px-4 py-2 flex items-center gap-2 text-sm font-semibold"
                style={{ color: p.color }}
              >
                {p.icon} {p.name}
              </motion.div>
            ))}
          </motion.div>
        </div>

        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 1.2 }}
          className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2"
          style={{ color: '#7a9060' }}
        >
          <span className="text-xs tracking-widest uppercase">Scroll</span>
          <motion.div animate={{ y: [0, 8, 0] }} transition={{ repeat: Infinity, duration: 1.5 }}>
            <ChevronDown size={18} />
          </motion.div>
        </motion.div>
      </section>

      <section className="relative py-20 overflow-hidden">
        <div className="divider-glow" />
        <div
          className="absolute inset-0 z-0"
          style={{
            background: 'linear-gradient(180deg, rgba(20,40,0,0.4) 0%, rgba(8,14,0,0) 100%)',
          }}
        />
        <div className="relative z-10 max-w-7xl mx-auto px-6 py-10">
          <div className="grid md:grid-cols-2 gap-10 items-stretch">
            <AnimatedSection delay={0.05}>
              <div className="h-full glass-card p-8 card-hover" style={{ borderColor: 'rgba(170,255,0,0.15)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-3" style={{ color: '#7a9060' }}>
                  Credibility + Founder
                </div>
                <h2 className="font-display font-extrabold text-3xl md:text-4xl mb-2" style={{ color: '#f5ffdc' }}>
                  Who's Behind <span className="gradient-text-lime">Thynker Groups</span>
                </h2>
                <div className="font-display font-bold text-lg mb-2" style={{ color: '#AAFF00' }}>
                  Jeaa Shrff
                </div>
                <p className="text-sm mb-4" style={{ color: '#7a9060' }}>
                  Hi, I’m the brain (and a little chaos energy) behind Thynker Groups.
                </p>
                <p className="text-sm mb-6" style={{ color: '#7a9060' }}>
                  Over the past 7 years, I’ve built strong networks with media, founders and entrepreneurs across Malaysia.
                  I don’t just know people — I know how to turn connections into momentum.
                </p>
                <div className="mb-4 font-display font-bold tracking-wide text-sm uppercase" style={{ color: '#AAFF00' }}>Experience</div>
                <ul className="flex flex-col gap-4">
                  <li>
                    <div className="text-sm font-semibold" style={{ color: '#f5ffdc' }}>5 Years Event Management</div>
                    <div className="text-xs" style={{ color: '#7a9060' }}>Built stages, crowds and real brand visibility.</div>
                  </li>
                  <li>
                    <div className="text-sm font-semibold" style={{ color: '#f5ffdc' }}>4 Years Titanium Circle (Richworks International)</div>
                    <div className="text-xs" style={{ color: '#7a9060' }}>Trained in serious business scaling frameworks.</div>
                  </li>
                  <li>
                    <div className="text-sm font-semibold" style={{ color: '#f5ffdc' }}>Trained 300+ stockists, agents and dropshippers</div>
                    <div className="text-xs" style={{ color: '#7a9060' }}>
                      Marketing strategy • Personal branding • Product positioning
                    </div>
                  </li>
                </ul>
                <div className="mt-6 mb-2 font-display font-bold tracking-wide text-sm uppercase" style={{ color: '#AAFF00' }}>Mission</div>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#7a9060' }}>SMEs deserve big-brand energy without corporate nonsense.</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>Build ecosystems.</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>Spark movements.</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>Turn quiet brands into loud conversations.</li>
                </ul>
              </div>
            </AnimatedSection>
            <div className="grid grid-cols-2 gap-6">
              {STATS.map((stat, i) => (
                <AnimatedSection key={stat.label} delay={i * 0.1}>
                  <div
                    className="glass-card-bright p-8 text-center card-hover h-full"
                    style={{ borderColor: `${stat.color}25` }}
                  >
                    <div className="flex justify-center mb-3" style={{ color: stat.color }}>{stat.icon}</div>
                    <div
                      className="font-display font-extrabold text-4xl md:text-5xl mb-2"
                      style={{
                        color: stat.color,
                        textShadow: `0 0 30px ${stat.color}90`,
                      }}
                    >
                      {stat.value}
                    </div>
                    <div className="text-sm" style={{ color: '#7a9060' }}>{stat.label}</div>
                  </div>
                </AnimatedSection>
              ))}
            </div>
          </div>
        </div>
        <div className="divider-glow" />
      </section>

      {/* ===== SERVICES ===== */}
      <section id="services" className="relative py-28 overflow-hidden">
        <div className="orb-lime" style={{ width: 600, height: 600, top: '-10%', right: '-10%', opacity: 0.6 }} />
        <div className="orb-cyan" style={{ width: 500, height: 500, bottom: '-5%', left: '-10%' }} />

        <div className="relative z-10 max-w-7xl mx-auto px-6">
          <AnimatedSection className="text-center mb-20">
            <span className="neon-tag mb-4 inline-block">Sidekick Marketing System</span>
            <h2 className="font-display font-extrabold mt-4 mb-5" style={{ fontSize: 'clamp(36px, 5vw, 64px)', color: '#f5ffdc' }}>
              Sidekick <span className="gradient-text-lime">Marketing System</span>
            </h2>
            <p className="text-lg max-w-xl mx-auto" style={{ color: '#7a9060' }}>
              Sidekick Marketing is a community-driven brand amplification system. Trusted micro-creators create organic conversations that trigger platform algorithms and drive real engagement.
            </p>
          </AnimatedSection>

          <div className="grid md:grid-cols-3 gap-8">
            {SERVICES.map((svc, i) => (
              <AnimatedSection key={svc.title} delay={i * 0.15}>
                <div
                  className={`glass-card card-hover p-8 h-full ${svc.glow} animated-border`}
                  style={{ borderColor: `${svc.color}25` }}
                >
                  <div
                    className="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
                    style={{
                      background: `${svc.color}15`,
                      border: `1px solid ${svc.color}40`,
                      color: svc.color,
                      boxShadow: `0 0 20px ${svc.color}30`,
                    }}
                  >
                    {svc.icon}
                  </div>

                  <span className={`neon-tag ${svc.tagClass} mb-4 inline-block`}>{svc.tag}</span>

                  <h3 className="font-display font-bold text-2xl mb-4" style={{ color: '#f5ffdc' }}>{svc.title}</h3>
                  <p className="text-sm leading-relaxed mb-6" style={{ color: '#7a9060' }}>{svc.desc}</p>

                  <ul className="flex flex-col gap-2">
                    {svc.features.map(f => (
                      <li key={f} className="flex items-center gap-2 text-sm" style={{ color: '#a8c070' }}>
                        <CheckCircle2 size={14} style={{ color: svc.color, flexShrink: 0 }} />
                        {f}
                      </li>
                    ))}
                  </ul>
                </div>
              </AnimatedSection>
            ))}
          </div>
          <div className="text-center mt-10">
            <span className="text-sm font-semibold" style={{ color: '#7a9060' }}>
              1000+ Sidekicks ready to amplify your brand.
            </span>
          </div>
        </div>
      </section>

      {/* ===== HOW IT WORKS ===== */}
      <section id="how-it-works" className="relative py-28 overflow-hidden">
        <div
          className="absolute inset-0 z-0"
          style={{
            background: 'radial-gradient(ellipse at 50% 50%, rgba(20,50,0,0.5) 0%, rgba(8,14,0,0) 70%)',
          }}
        />
        <div className="orb-lime" style={{ width: 500, height: 500, top: '20%', left: '50%', transform: 'translateX(-50%)', opacity: 0.25 }} />

        <div className="relative z-10 max-w-6xl mx-auto px-6">
          <AnimatedSection className="text-center mb-20">
            <span className="neon-tag-cyan neon-tag mb-4 inline-block">Process</span>
            <h2 className="font-display font-extrabold mt-4 mb-5" style={{ fontSize: 'clamp(36px, 5vw, 64px)', color: '#f5ffdc' }}>
              How <span className="gradient-text-cyan">Thynker Campaigns</span> Work
            </h2>
            <p className="text-lg max-w-xl mx-auto" style={{ color: '#7a9060' }} />
          </AnimatedSection>

          <div className="grid md:grid-cols-4 gap-6">
            {HOW_IT_WORKS.map((step, i) => (
              <AnimatedSection key={step.step} delay={i * 0.12}>
                <div className="glass-card p-7 card-hover relative overflow-hidden" style={{ borderColor: `${step.color}20` }}>
                  <div
                    className="absolute -top-2 -right-2 font-display font-extrabold text-8xl opacity-[0.06] select-none"
                    style={{ color: step.color }}
                  >
                    {step.step}
                  </div>
                  <div className="text-sm font-bold mb-4 font-display" style={{ color: step.color }}>
                    STEP {step.step}
                  </div>
                  <h3 className="font-display font-bold text-xl mb-3" style={{ color: '#f5ffdc' }}>{step.title}</h3>
                  <p className="text-sm leading-relaxed" style={{ color: '#7a9060' }}>{step.desc}</p>
                </div>
              </AnimatedSection>
            ))}
          </div>

          <div className="mt-10 grid md:grid-cols-2 gap-6">
            <AnimatedSection delay={0.1}>
              <div className="glass-card p-7 card-hover" style={{ borderColor: 'rgba(170,255,0,0.15)' }}>
                <div className="text-sm font-bold uppercase tracking-widest mb-3" style={{ color: '#AAFF00' }}>
                  Problem
                </div>
                <p className="text-sm mb-3" style={{ color: '#7a9060' }}>
                  Brands struggle to manage affiliate creators because:
                </p>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#7a9060' }}>• difficult coordination</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>• messy data</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>• inconsistent campaigns</li>
                </ul>
              </div>
            </AnimatedSection>
            <AnimatedSection delay={0.2}>
              <div className="glass-card p-7 card-hover glow-lime" style={{ borderColor: 'rgba(170,255,0,0.15)' }}>
                <div className="text-sm font-bold uppercase tracking-widest mb-3" style={{ color: '#AAFF00' }}>
                  Thynker Solution
                </div>
                <p className="text-sm mb-3" style={{ color: '#7a9060' }}>
                  We manage everything:
                </p>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#7a9060' }}>• affiliate recruitment</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>• onboarding</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>• campaign management</li>
                  <li className="text-sm" style={{ color: '#7a9060' }}>• performance reporting</li>
                </ul>
                <div className="mt-4 font-display font-bold text-center" style={{ color: '#f5ffdc' }}>
                  Less Chaos <span style={{ color: '#AAFF00' }}>→</span> Better ROI
                </div>
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* ===== RESULTS / TESTIMONIALS ===== */}
      <section id="results" className="relative py-28 overflow-hidden">
        <div className="orb-lime" style={{ width: 600, height: 600, top: '0%', right: '-10%', opacity: 0.5 }} />
        <div className="orb-cyan" style={{ width: 500, height: 500, bottom: '0%', left: '-5%' }} />

        <div className="relative z-10 max-w-6xl mx-auto px-6">
          <AnimatedSection className="text-center mb-20">
            <span className="neon-tag mb-4 inline-block">Case Studies</span>
            <h2 className="font-display font-extrabold mt-4 mb-5" style={{ fontSize: 'clamp(36px, 5vw, 64px)', color: '#f5ffdc' }}>
              Real{' '}
              <span className="gradient-text-lime">Results</span>
            </h2>
          </AnimatedSection>

          <div className="grid md:grid-cols-2 gap-8">
            <AnimatedSection delay={0.1}>
              <div className="glass-card-bright p-8 card-hover glow-lime h-full" style={{ borderColor: 'rgba(170,255,0,0.2)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Case Study 1</div>
                <h3 className="font-display font-bold text-2xl mb-3" style={{ color: '#f5ffdc' }}>LUXE Cosmetics</h3>
                <p className="text-sm mb-5" style={{ color: '#a8c070' }}>
                  Sidekick accounts created product discussion threads.
                </p>
                <div className="text-xs font-semibold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Result</div>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#a8c070' }}>• High engagement</li>
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Increased product discovery</li>
                </ul>
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.15}>
              <div className="glass-card p-8 card-hover glow-cyan h-full" style={{ borderColor: 'rgba(0,191,255,0.15)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Case Study 2</div>
                <h3 className="font-display font-bold text-2xl mb-3" style={{ color: '#f5ffdc' }}>Perfume Paradise</h3>
                <p className="text-sm mb-5" style={{ color: '#a8c070' }}>
                  Creators posted lifestyle content featuring the perfume.
                </p>
                <div className="text-xs font-semibold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Result</div>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Social proof</li>
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Increased brand trust</li>
                </ul>
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.2}>
              <div className="glass-card p-8 card-hover glow-lime h-full" style={{ borderColor: 'rgba(170,255,0,0.15)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Case Study 3</div>
                <h3 className="font-display font-bold text-2xl mb-3" style={{ color: '#f5ffdc' }}>WAWA Cosmetic</h3>
                <p className="text-sm mb-5" style={{ color: '#a8c070' }}>
                  Product discussion threads triggered algorithm distribution.
                </p>
                <div className="text-xs font-semibold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Result</div>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Viral engagement</li>
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Product curiosity</li>
                </ul>
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.25}>
              <div className="glass-card p-8 card-hover glow-cyan h-full" style={{ borderColor: 'rgba(0,191,255,0.15)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Case Study 4</div>
                <h3 className="font-display font-bold text-2xl mb-3" style={{ color: '#f5ffdc' }}>Hijabistahub Fashion</h3>
                <p className="text-sm mb-5" style={{ color: '#a8c070' }}>
                  Community discussions and comment interactions.
                </p>
                <div className="text-xs font-semibold uppercase tracking-widest mb-2" style={{ color: '#7a9060' }}>Result</div>
                <ul className="flex flex-col gap-2">
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Increased reach</li>
                  <li className="text-sm" style={{ color: '#a8c070' }}>• Community trust</li>
                </ul>
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* ===== PRICING ===== */}
      <section id="pricing" className="relative py-28 overflow-hidden">
        <div
          className="absolute inset-0 z-0"
          style={{
            background: 'radial-gradient(ellipse at 30% 50%, rgba(15,40,0,0.6) 0%, rgba(8,14,0,0) 70%)',
          }}
        />
        <div className="orb-cyan" style={{ width: 600, height: 600, top: '10%', right: '-15%' }} />

        <div className="relative z-10 max-w-6xl mx-auto px-6">
          <AnimatedSection className="text-center mb-20">
            <span className="neon-tag-cyan neon-tag mb-4 inline-block">Pricing</span>
            <h2 className="font-display font-extrabold mt-4 mb-5" style={{ fontSize: 'clamp(36px, 5vw, 64px)', color: '#f5ffdc' }}>
              Simple,{' '}
              <span className="gradient-text-lime">Transparent</span>{' '}
              Plans
            </h2>
            <p className="text-lg max-w-xl mx-auto" style={{ color: '#7a9060' }}>
              Choose the right plan to accelerate your brand's growth.
            </p>
          </AnimatedSection>

          <AnimatedSection>
            <div className="text-center mb-6">
              <span className="neon-tag mb-3 inline-block">Sidekick Marketing Packages</span>
              <h3 className="font-display font-extrabold text-2xl" style={{ color: '#f5ffdc' }}>Sidekick Marketing Packages</h3>
            </div>
            <div className="grid md:grid-cols-4 gap-6 mb-12">
              {SIDEKICK_PRICING.map((pkg, i) => (
                <AnimatedSection key={pkg.name} delay={i * 0.08}>
                  <div
                    className={`glass-card card-hover p-7 h-full flex flex-col relative overflow-hidden ${pkg.featured ? 'glow-lime' : ''}`}
                    style={{ borderColor: pkg.featured ? `${pkg.color}45` : `${pkg.color}20` }}
                  >
                    {pkg.featured && (
                      <div className="absolute top-0 left-0 right-0 h-1 rounded-t-xl" style={{ background: `linear-gradient(90deg, transparent, ${pkg.color}, transparent)` }} />
                    )}
                    <h4 className="font-display font-bold text-xl mb-2" style={{ color: '#f5ffdc' }}>{pkg.name}</h4>
                    <div className="text-sm mb-4" style={{ color: '#7a9060' }}>Sidekicks: <span style={{ color: pkg.color }} className="font-semibold">{pkg.sidekicks}</span></div>
                    <div className="font-display font-extrabold text-4xl mb-6" style={{ color: pkg.color, textShadow: `0 0 20px ${pkg.color}60` }}>
                      {pkg.price}
                    </div>
                    <a href="#join" className="w-full py-3 rounded-full text-sm font-bold text-center transition-all hover:scale-105 shimmer-btn" style={{ color: '#080e00' }}>
                      Launch Campaign <ArrowRight size={14} className="inline ml-1" />
                    </a>
                  </div>
                </AnimatedSection>
              ))}
            </div>
          </AnimatedSection>

          <AnimatedSection>
            <div className="text-center mb-6">
              <span className="neon-tag-cyan neon-tag mb-3 inline-block">Affiliate Content Packages</span>
              <h3 className="font-display font-extrabold text-2xl" style={{ color: '#f5ffdc' }}>Affiliate Content Packages</h3>
            </div>
            <div className="grid md:grid-cols-3 gap-6 mb-8">
              {AFFILIATE_PACKAGES.map((pkg, i) => (
                <AnimatedSection key={pkg.name} delay={i * 0.08}>
                  <div className="glass-card p-7 card-hover h-full flex flex-col" style={{ borderColor: `${pkg.color}20` }}>
                    <h4 className="font-display font-bold text-xl mb-2" style={{ color: '#f5ffdc' }}>{pkg.name}</h4>
                    <div className="text-sm" style={{ color: '#7a9060' }}>
                      <div>Affiliate Creators: <span className="font-semibold" style={{ color: pkg.color }}>{pkg.affiliates}</span></div>
                      <div>2 videos per affiliate</div>
                      {pkg.note ? <div>{pkg.note}</div> : null}
                    </div>
                    <div className="font-display font-extrabold text-3xl mt-5 mb-6" style={{ color: pkg.color, textShadow: `0 0 20px ${pkg.color}60` }}>
                      {pkg.price}
                    </div>
                    <a href="#join" className="w-full py-3 rounded-full text-sm font-bold text-center transition-all hover:scale-105 glass-card" style={{ color: pkg.color, borderColor: `${pkg.color}30` }}>
                      Enquire <ArrowRight size={14} className="inline ml-1" />
                    </a>
                  </div>
                </AnimatedSection>
              ))}
            </div>
            <div className="text-center mb-4" style={{ color: '#7a9060' }}>Premium Packages</div>
            <div className="grid md:grid-cols-2 gap-6">
              {PREMIUM_PACKAGES.map((pkg, i) => (
                <AnimatedSection key={pkg.name} delay={i * 0.08}>
                  <div className="glass-card p-7 card-hover h-full flex flex-col" style={{ borderColor: `${pkg.color}20` }}>
                    <h4 className="font-display font-bold text-xl mb-2" style={{ color: '#f5ffdc' }}>{pkg.name}</h4>
                    <div className="text-sm mb-2" style={{ color: '#7a9060' }}>Affiliate Creators: <span className="font-semibold" style={{ color: pkg.color }}>{pkg.affiliates}</span></div>
                    <div className="font-display font-extrabold text-3xl mb-6" style={{ color: pkg.color, textShadow: `0 0 20px ${pkg.color}60` }}>
                      {pkg.price}
                    </div>
                    <a href="#join" className="w-full py-3 rounded-full text-sm font-bold text-center transition-all hover:scale-105 glass-card" style={{ color: pkg.color, borderColor: `${pkg.color}30` }}>
                      Enquire <ArrowRight size={14} className="inline ml-1" />
                    </a>
                  </div>
                </AnimatedSection>
              ))}
            </div>
          </AnimatedSection>
        </div>
      </section>

      <section id="join" className="relative py-28 overflow-hidden" ref={joinRef}>
        <div className="orb-lime" style={{ width: 600, height: 600, top: '-10%', right: '-10%', opacity: 0.35 }} />
        <div className="orb-cyan" style={{ width: 500, height: 500, bottom: '-5%', left: '-10%', opacity: 0.35 }} />
        <div className="relative z-10 max-w-7xl mx-auto px-6">
          <AnimatedSection className="text-center mb-16">
            <span className="neon-tag mb-4 inline-block">Join Thynker Ecosystem</span>
            <h2 className="font-display font-extrabold mt-2 mb-3" style={{ fontSize: 'clamp(32px, 5vw, 56px)', color: '#f5ffdc' }}>
              Join Thynker Ecosystem
            </h2>
            <div className="flex items-center justify-center gap-4 mt-4">
              <a href="#join" className="shimmer-btn px-6 py-3 rounded-full font-bold text-sm" style={{ color: '#080e00' }}>
                Launch Campaign
              </a>
              <a href="#join" className="glass-card px-6 py-3 rounded-full font-bold text-sm" style={{ color: '#AAFF00', borderColor: 'rgba(170,255,0,0.2)' }}>
                Become a Sidekick
              </a>
            </div>
          </AnimatedSection>
          <div className="grid md:grid-cols-2 gap-8">
            <AnimatedSection delay={0.1}>
              <div className="glass-card-bright p-8 card-hover h-full" style={{ borderColor: 'rgba(170,255,0,0.2)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-3" style={{ color: '#7a9060' }}>For Brands</div>
                <h3 className="font-display font-extrabold text-2xl mb-6" style={{ color: '#f5ffdc' }}>
                  Grow Your Brand With 1000+ Sidekicks
                </h3>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinbrand-name" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Name</label>
                    <input id="joinbrand-name" type="text" placeholder="Name" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label htmlFor="joinbrand-email" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Email</label>
                    <input id="joinbrand-email" type="email" placeholder="email@brand.com" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} />
                  </div>
                </div>
                <div className="mb-5">
                  <label htmlFor="joinbrand-brand" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Brand Name</label>
                  <input id="joinbrand-brand" type="text" placeholder="Brand name" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                    style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} />
                </div>
                <div className="mb-5">
                  <label htmlFor="joinbrand-category" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Product Category</label>
                  <select id="joinbrand-category" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                    style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} defaultValue="">
                    <option value="" disabled>Select category</option>
                    <option>Beauty</option>
                    <option>Fashion</option>
                    <option>Lifestyle</option>
                    <option>Food</option>
                    <option>Tech</option>
                    <option>Other</option>
                  </select>
                </div>
                <div className="mb-5">
                  <label htmlFor="joinbrand-goal" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Campaign Goal</label>
                  <textarea id="joinbrand-goal" rows={4} placeholder="Describe your campaign goal"
                    className="w-full rounded-xl px-4 py-3 text-sm transition-all resize-none"
                    style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} />
                </div>
                <div className="mb-6">
                  <label htmlFor="joinbrand-budget" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Budget Range</label>
                  <select id="joinbrand-budget" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                    style={{ background: 'rgba(170,255,0,0.05)', border: '1px solid rgba(170,255,0,0.15)', color: '#f5ffdc' }} defaultValue="">
                    <option value="" disabled>Select your range</option>
                    <option>RM1,000 – RM5,000</option>
                    <option>RM5,000 – RM15,000</option>
                    <option>RM15,000 – RM50,000</option>
                    <option>RM50,000+</option>
                  </select>
                </div>
                <a href="#join" className="w-full shimmer-btn py-3 rounded-xl font-bold text-center inline-block hover:scale-105 transition-transform"
                  style={{ color: '#080e00' }}>
                  Launch My Campaign
                </a>
              </div>
            </AnimatedSection>
            <AnimatedSection delay={0.15}>
              <div className="glass-card p-8 card-hover h-full" style={{ borderColor: 'rgba(0,191,255,0.15)' }}>
                <div className="text-xs font-bold uppercase tracking-widest mb-3" style={{ color: '#7a9060' }}>For Creators</div>
                <h3 className="font-display font-extrabold text-2xl mb-1" style={{ color: '#f5ffdc' }}>
                  Join The Thynker Sidekick Network
                </h3>
                <p className="text-sm mb-6" style={{ color: '#7a9060' }}>
                  Become part of a trusted creator community helping brands grow through authentic conversations.
                </p>
                <div className="mb-5">
                  <div className="font-display font-bold text-sm uppercase tracking-widest mb-2" style={{ color: '#AAFF00' }}>Why Become a Sidekick?</div>
                  <ul className="flex flex-col gap-2">
                    {['Collaborate with real brands','Receive product samples','Paid campaign opportunities','Grow your creator portfolio','Join a trusted creator network'].map(text => (
                      <li key={text} className="flex items-center gap-2 text-sm" style={{ color: '#a8c070' }}>
                        <CheckCircle2 size={14} style={{ color: '#AAFF00', flexShrink: 0 }} />
                        {text}
                      </li>
                    ))}
                  </ul>
                </div>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinsidekick-fullname" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Full Name</label>
                    <input id="joinsidekick-fullname" type="text" placeholder="Full name" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label htmlFor="joinsidekick-phone" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Phone Number</label>
                    <input id="joinsidekick-phone" type="tel" placeholder="+60..." className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                </div>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinsidekick-email" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Email</label>
                    <input id="joinsidekick-email" type="email" placeholder="email@example.com" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label htmlFor="joinsidekick-followers" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Follower Count</label>
                    <input id="joinsidekick-followers" type="number" placeholder="e.g. 12000" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                </div>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinsidekick-tiktok" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>TikTok Username</label>
                    <input id="joinsidekick-tiktok" type="text" placeholder="@handle" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label htmlFor="joinsidekick-instagram" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Instagram Username</label>
                    <input id="joinsidekick-instagram" type="text" placeholder="@handle" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                </div>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinsidekick-niche" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Content Niche</label>
                    <input id="joinsidekick-niche" type="text" placeholder="e.g. Beauty, Fashion" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label htmlFor="joinsidekick-location" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>City / Country</label>
                    <input id="joinsidekick-location" type="text" placeholder="City, Country" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                </div>
                <div className="grid md:grid-cols-2 gap-5 mb-5">
                  <div>
                    <label htmlFor="joinsidekick-avgviews" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Average Views (optional)</label>
                    <input id="joinsidekick-avgviews" type="number" placeholder="e.g. 5000" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                      style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Content Type</label>
                    <div className="flex flex-wrap gap-3">
                      {['Beauty','Fashion','Lifestyle','Food','Tech','Other'].map((opt, idx) => (
                        <label key={opt} htmlFor={`joinsidekick-content-${idx}`} className="flex items-center gap-2 text-xs px-3 py-2 rounded-full glass-card"
                          style={{ color: '#7a9060', borderColor: 'rgba(0,191,255,0.15)' }}>
                          <input id={`joinsidekick-content-${idx}`} type="checkbox" />
                          {opt}
                        </label>
                      ))}
                    </div>
                  </div>
                </div>
                <div className="mb-5">
                  <label htmlFor="joinsidekick-upload" className="block text-xs font-semibold mb-2 uppercase tracking-widest" style={{ color: '#7a9060' }}>Upload Sample Content (optional)</label>
                  <input id="joinsidekick-upload" type="file" className="w-full rounded-xl px-4 py-3 text-sm transition-all"
                    style={{ background: 'rgba(0,191,255,0.05)', border: '1px solid rgba(0,191,255,0.15)', color: '#f5ffdc' }} />
                </div>
                <div className="mb-6 flex items-center gap-2">
                  <input id="joinsidekick-agree" type="checkbox" />
                  <label htmlFor="joinsidekick-agree" className="text-xs" style={{ color: '#7a9060' }}>
                    I agree to collaborate with brands through Thynker campaigns
                  </label>
                </div>
                <a href="#join" className="w-full shimmer-btn py-3 rounded-xl font-bold text-center inline-block hover:scale-105 transition-transform"
                  style={{ color: '#080e00' }}>
                  Apply as Sidekick
                </a>
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>
      {/* Contact section removed per request */}

      {/* ===== FOOTER ===== */}
      <footer className="relative" style={{ borderTop: '1px solid rgba(170,255,0,0.08)' }}>
        <div className="max-w-7xl mx-auto px-6 py-16">
          <div className="grid md:grid-cols-2 gap-12 mb-12">
            <div>
              <div className="flex items-center gap-2 mb-4">
                <div
                  className="w-8 h-8 rounded-lg flex items-center justify-center"
                  style={{ background: '#AAFF00', boxShadow: '0 0 16px rgba(170,255,0,0.5)' }}
                >
                  <Sparkles size={16} style={{ color: '#080e00' }} />
                </div>
                <span className="font-display font-extrabold text-xl" style={{ letterSpacing: '0.5px' }}>
                  THYNKER GROUPS
                </span>
              </div>
              <p className="text-sm leading-relaxed mb-1 max-w-xs" style={{ color: '#7a9060' }}>
                Sidekick Marketing Ecosystem
              </p>
              <p className="text-sm leading-relaxed mb-6 max-w-xs" style={{ color: '#7a9060' }}>
                Amplifying brands through community driven growth.
              </p>
              <div className="flex gap-4">
                {[Instagram, Twitter, Youtube].map((Icon, i) => (
                  <a
                    key={i}
                    href="#"
                    className="w-9 h-9 rounded-full glass-card flex items-center justify-center hover:scale-110 transition-transform"
                    style={{ color: '#7a9060' }}
                    onMouseEnter={e => (e.currentTarget.style.color = '#AAFF00')}
                    onMouseLeave={e => (e.currentTarget.style.color = '#7a9060')}
                  >
                    <Icon size={16} />
                  </a>
                ))}
              </div>
            </div>

            <div className="text-center md:text-right">
              <h4 className="font-display font-bold text-sm uppercase tracking-widest mb-5" style={{ color: '#AAFF00' }}>Highlights</h4>
              <div className="flex flex-col gap-2">
                <div className="text-sm" style={{ color: '#7a9060' }}>1000+ <span className="font-semibold" style={{ color: '#AAFF00' }}>Active Sidekicks</span></div>
                <div className="text-sm" style={{ color: '#7a9060' }}>50+ <span className="font-semibold" style={{ color: '#AAFF00' }}>Brands Supported</span></div>
              </div>
            </div>
          </div>

          <div className="divider-glow mb-8" />

          <div className="flex flex-col md:flex-row justify-between items-center gap-4 text-sm" style={{ color: '#7a9060' }}>
            <span>© 2025 Thynker. All rights reserved.</span>
            <div className="flex items-center gap-2">
              <Shield size={14} style={{ color: '#AAFF00' }} />
              <span>Trusted by 50+ brands worldwide</span>
            </div>
            <div className="flex gap-6">
              <a href="#" className="transition-colors" onMouseEnter={e => (e.currentTarget.style.color = '#AAFF00')} onMouseLeave={e => (e.currentTarget.style.color = '#7a9060')}>Privacy</a>
              <a href="#" className="transition-colors" onMouseEnter={e => (e.currentTarget.style.color = '#AAFF00')} onMouseLeave={e => (e.currentTarget.style.color = '#7a9060')}>Terms</a>
            </div>
          </div>
        </div>
      </footer>
      {!hideSticky && (
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5, delay: 0.2 }}
        style={{
          position: 'fixed',
          bottom: 24,
          right: 24,
          zIndex: 60,
        }}
      >
        <a
          href="https://wa.me/60134331204"
          aria-label="Chat With Thynker on WhatsApp"
          className="glass-card px-4 py-3 rounded-full flex items-center gap-2 font-semibold hover:scale-105 transition-transform"
          style={{
            color: '#25D366',
            borderColor: 'rgba(37,211,102,0.35)',
            background: 'rgba(37,211,102,0.12)',
            boxShadow: '0 0 20px rgba(37,211,102,0.15)',
            backdropFilter: 'blur(6px)',
          }}
        >
          <MessageSquare size={16} />
          Chat With Thynker
        </a>
      </motion.div>
      )}
      {!hideSticky && (
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5, delay: 0.3 }}
        style={{
          position: 'fixed',
          bottom: 24,
          left: '50%',
          transform: 'translateX(-50%)',
          zIndex: 60,
        }}
      >
        <a
          href="#join"
          aria-label="Launch Campaign"
          className="shimmer-btn px-6 py-3 rounded-full font-bold flex items-center gap-2 hover:scale-105 transition-transform"
          style={{ color: '#080e00' }}
        >
          Launch Campaign <ArrowRight size={16} />
        </a>
      </motion.div>
      )}
    </div>
  );
}
