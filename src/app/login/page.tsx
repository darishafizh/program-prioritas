"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Lock, Mail, Waves, Anchor, Fish } from "lucide-react";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const { login, isAuthenticated, loading } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!loading && isAuthenticated) {
      router.push("/portal/dashboard/knmp");
    }
  }, [isAuthenticated, loading, router]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setIsLoading(true);

    // Simulate network delay
    await new Promise((resolve) => setTimeout(resolve, 800));

    const success = login(email, password);
    if (success) {
      router.push("/portal/dashboard/knmp");
    } else {
      setError("Email atau password tidak valid");
      setIsLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-navy-800">
        <div className="w-12 h-12 border-4 border-accent border-t-transparent rounded-full animate-spin" />
      </div>
    );
  }

  return (
    <div className="min-h-screen flex relative overflow-hidden">
      {/* Left decorative panel */}
      <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-navy-800 via-navy-600 to-accent relative flex-col items-center justify-center p-12">
        {/* Animated background elements */}
        <div className="absolute inset-0 overflow-hidden">
          <div className="absolute top-20 left-10 opacity-10">
            <Waves className="w-32 h-32 text-white animate-pulse" />
          </div>
          <div className="absolute bottom-32 right-16 opacity-10">
            <Anchor className="w-24 h-24 text-white animate-pulse" style={{ animationDelay: "1s" }} />
          </div>
          <div className="absolute top-1/2 left-1/4 opacity-5">
            <Fish className="w-40 h-40 text-white animate-pulse" style={{ animationDelay: "2s" }} />
          </div>
          {/* Gradient orbs */}
          <div className="absolute top-1/4 right-1/4 w-64 h-64 bg-accent/20 rounded-full blur-3xl" />
          <div className="absolute bottom-1/4 left-1/3 w-48 h-48 bg-blue-400/10 rounded-full blur-3xl" />
        </div>

        <div className="relative z-10 text-center max-w-md">
          {/* KKP Logo placeholder */}
          <div className="w-28 h-28 mx-auto mb-8 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-2xl">
            <div className="text-center">
              <Fish className="w-12 h-12 text-white mx-auto mb-1" />
              <span className="text-white font-bold text-xs tracking-wider">KKP</span>
            </div>
          </div>
          <h1 className="text-5xl font-extrabold text-white mb-5 tracking-tight drop-shadow-lg">
            Roren One
          </h1>
          <p className="text-blue-100/90 text-lg font-medium leading-relaxed tracking-wide">
            Sistem Portal Terpadu
            <br />
            Kementerian Kelautan dan Perikanan
            <br />
            Republik Indonesia
          </p>
          <div className="mt-8 flex items-center justify-center gap-3">
            <div className="h-px w-12 bg-white/20" />
            <span className="text-blue-300/60 text-sm">Monitoring & Evaluasi</span>
            <div className="h-px w-12 bg-white/20" />
          </div>
        </div>
      </div>

      {/* Right login form */}
      <div className="flex-1 flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50/30 p-6">
        <div className="w-full max-w-md">
          {/* Mobile logo */}
          <div className="lg:hidden text-center mb-8">
            <div className="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-navy-600 to-accent rounded-2xl flex items-center justify-center shadow-xl">
              <Fish className="w-10 h-10 text-white" />
            </div>
            <h1 className="text-2xl font-bold gradient-text">Roren One</h1>
            <p className="text-sm text-gray-500 mt-1">Portal KKP RI</p>
          </div>

          <div className="bg-white rounded-2xl shadow-xl shadow-navy-600/5 border border-gray-100 p-8">
            <div className="mb-8">
              <h2 className="text-3xl font-extrabold text-navy-800 tracking-tight">Masuk</h2>
              <p className="text-gray-500 mt-2 text-sm">
                Silakan masuk dengan akun terdaftar Anda
              </p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              <div className="space-y-2">
                <Label htmlFor="email" className="text-sm font-medium text-gray-700">
                  Email
                </Label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                  <Input
                    id="email"
                    type="email"
                    placeholder="nama@kkp.go.id"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="pl-10 h-11 bg-gray-50/50 border-gray-200 focus:border-accent focus:ring-accent/20 rounded-xl"
                    required
                  />
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="password" className="text-sm font-medium text-gray-700">
                  Password
                </Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                  <Input
                    id="password"
                    type="password"
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="pl-10 h-11 bg-gray-50/50 border-gray-200 focus:border-accent focus:ring-accent/20 rounded-xl"
                    required
                  />
                </div>
              </div>

              {error && (
                <div className="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3 animate-fade-in">
                  {error}
                </div>
              )}

              <Button
                type="submit"
                disabled={isLoading}
                className="w-full h-11 bg-gradient-to-r from-navy-600 to-accent hover:from-navy-700 hover:to-accent-dark text-white font-semibold rounded-xl shadow-lg shadow-accent/25 transition-all duration-300 hover:shadow-xl hover:shadow-accent/30 hover:-translate-y-0.5"
              >
                {isLoading ? (
                  <div className="flex items-center gap-2">
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    <span>Memproses...</span>
                  </div>
                ) : (
                  "Masuk"
                )}
              </Button>
            </form>

            <div className="mt-6 pt-6 border-t border-gray-100">
              <p className="text-center text-xs text-gray-400">
                © 2024 Kementerian Kelautan dan Perikanan RI
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
