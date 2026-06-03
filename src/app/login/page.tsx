"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Lock, Mail, Fish } from "lucide-react";

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
    <div 
      className="min-h-screen flex relative overflow-hidden bg-cover bg-center"
      style={{ backgroundImage: "url('/images/bg-login.png')" }}
    >
      {/* Dark overlay for better readability */}
      <div className="absolute inset-0 bg-navy-900/60 backdrop-blur-[2px]"></div>

      {/* Centered Content */}
      <div className="relative z-10 w-full flex flex-col items-center justify-center p-4 sm:p-8">
        
        {/* Branding header above card */}
        <div className="text-center mb-8 animate-fade-in">
          <div className="w-20 h-20 mx-auto mb-4 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-2xl">
            <Fish className="w-10 h-10 text-white" />
          </div>
          <h1 className="text-4xl sm:text-5xl font-extrabold text-white mb-2 tracking-tight drop-shadow-lg">
            Roren One
          </h1>
          <p className="text-blue-100/90 text-sm sm:text-base font-medium tracking-wide drop-shadow-md">
            Portal Terpadu Kementerian Kelautan dan Perikanan
          </p>
        </div>

        {/* Login Card */}
        <div className="w-full max-w-[440px] bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 sm:p-10 animate-slide-up">
          <div className="mb-8 text-center">
            <h2 className="text-3xl font-extrabold text-navy-800 tracking-tight">Masuk</h2>
            <p className="text-gray-500 mt-2 text-sm font-medium">
              Silakan masuk dengan akun terdaftar Anda
            </p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-5">
            <div className="space-y-2">
              <Label htmlFor="email" className="text-sm font-bold text-gray-700">
                Email
              </Label>
              <div className="relative">
                <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <Input
                  id="email"
                  type="email"
                  placeholder="nama@kkp.go.id"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="pl-11 bg-gray-50/50 border-gray-200 focus:border-accent focus:ring-accent/20 transition-all duration-300"
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="password" className="text-sm font-bold text-gray-700">
                Password
              </Label>
              <div className="relative">
                <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <Input
                  id="password"
                  type="password"
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="pl-11 bg-gray-50/50 border-gray-200 focus:border-accent focus:ring-accent/20 transition-all duration-300"
                  required
                />
              </div>
            </div>

            {error && (
              <div className="bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl px-4 py-3 animate-fade-in text-center">
                {error}
              </div>
            )}

            <Button
              type="submit"
              disabled={isLoading}
              className="w-full mt-2 bg-gradient-to-r from-navy-600 to-accent hover:from-navy-700 hover:to-accent-dark text-white shadow-lg shadow-accent/20 transition-all duration-300 hover:shadow-xl hover:shadow-accent/30"
            >
              {isLoading ? (
                <div className="flex items-center gap-2">
                  <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                  <span>Memproses...</span>
                </div>
              ) : (
                "Masuk ke Portal"
              )}
            </Button>
          </form>

          <div className="mt-8 pt-6 border-t border-gray-100">
            <p className="text-center text-xs text-gray-400 font-medium">
              © 2024 Kementerian Kelautan dan Perikanan RI
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
